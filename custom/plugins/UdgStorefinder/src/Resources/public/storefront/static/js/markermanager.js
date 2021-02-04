function MarkerManager(c, b) {
    var a = this;
    a.map_ = c;
    a.mapZoom_ = c.getZoom();
    a.projectionHelper_ = new ProjectionHelperOverlay(c);
    google.maps.event.addListener(a.projectionHelper_, 'ready', function() {
        a.projection_ = this.getProjection();
        a.initialize(c, b)
    })
}
MarkerManager.prototype.initialize = function(d, b) {
    var f = this;
    b = b || {};
    f.tileSize_ = MarkerManager.DEFAULT_TILE_SIZE_;
    var h = d.mapTypes;
    var i = 1;
    for (var c in h) {
        if (h.hasOwnProperty(c) && h.get(c) && h.get(c).maxZoom === 'number') {
            var g = d.mapTypes.get(c).maxZoom;
            if (g > i) {
                i = g
            }
        }
    }
    f.maxZoom_ = b.maxZoom || 19;
    f.trackMarkers_ = b.trackMarkers;
    f.show_ = b.show || true;
    var e;
    if (typeof b.borderPadding === 'number') {
        e = b.borderPadding
    } else {
        e = MarkerManager.DEFAULT_BORDER_PADDING_
    }
    f.swPadding_ = new google.maps.Size(-e, e);
    f.nePadding_ = new google.maps.Size(e, -e);
    f.borderPadding_ = e;
    f.gridWidth_ = {};
    f.grid_ = {};
    f.grid_[f.maxZoom_] = {};
    f.numMarkers_ = {};
    f.numMarkers_[f.maxZoom_] = 0;
    google.maps.event.addListener(d, 'dragend', function() {
        f.onMapMoveEnd_()
    });
    google.maps.event.addListener(d, 'idle', function() {
        f.onMapMoveEnd_()
    });
    google.maps.event.addListener(d, 'zoom_changed', function() {
        f.onMapMoveEnd_()
    });
    f.removeOverlay_ = function(a) {
        a.setMap(null);
        f.shownMarkers_--
    };
    f.addOverlay_ = function(a) {
        if (f.show_) {
            a.setMap(f.map_);
            f.shownMarkers_++
        }
    };
    f.resetManager_();
    f.shownMarkers_ = 0;
    f.shownBounds_ = f.getMapGridBounds_();
    google.maps.event.trigger(f, 'loaded')
};
MarkerManager.DEFAULT_TILE_SIZE_ = 1024;
MarkerManager.DEFAULT_BORDER_PADDING_ = 100;
MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE = 256;
MarkerManager.prototype.resetManager_ = function() {
    var a = MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE;
    for (var b = 0; b <= this.maxZoom_; ++b) {
        this.grid_[b] = {};
        this.numMarkers_[b] = 0;
        this.gridWidth_[b] = Math.ceil(a / this.tileSize_);
        a <<= 1
    }
};
MarkerManager.prototype.clearMarkers = function() {
    this.processAll_(this.shownBounds_, this.removeOverlay_);
    this.resetManager_()
};
MarkerManager.prototype.getTilePoint_ = function(a, c, b) {
    var d = this.projectionHelper_.LatLngToPixel(a, c);
    var e = new google.maps.Point(Math.floor((d.x + b.width) / this.tileSize_), Math.floor((d.y + b.height) / this.tileSize_));
    return e
};
MarkerManager.prototype.addMarkerBatch_ = function(i, d, j) {
    var f = this;
    var e = i.getPosition();
    i.MarkerManager_minZoom = d;
    if (this.trackMarkers_) {
        google.maps.event.addListener(i, 'changed', function(a, b, c) {
            f.onMarkerMoved_(a, b, c)
        })
    }
    var h = this.getTilePoint_(e, j, new google.maps.Size(0, 0, 0, 0));
    for (var g = j; g >= d; g--) {
        var k = this.getGridCellCreate_(h.x, h.y, g);
        k.push(i);
        h.x = h.x >> 1;
        h.y = h.y >> 1
    }
};
MarkerManager.prototype.isGridPointVisible_ = function(d) {
    var b = this.shownBounds_.minY <= d.y && d.y <= this.shownBounds_.maxY;
    var e = this.shownBounds_.minX;
    var c = e <= d.x && d.x <= this.shownBounds_.maxX;
    if (!c && e < 0) {
        var a = this.gridWidth_[this.shownBounds_.z];
        c = e + a <= d.x && d.x <= a - 1
    }
    return b && c
};
MarkerManager.prototype.onMarkerMoved_ = function(f, b, g) {
    var c = this.maxZoom_;
    var a = false;
    var d = this.getTilePoint_(b, c, new google.maps.Size(0, 0, 0, 0));
    var e = this.getTilePoint_(g, c, new google.maps.Size(0, 0, 0, 0));
    while (c >= 0 && (d.x !== e.x || d.y !== e.y)) {
        var h = this.getGridCellNoCreate_(d.x, d.y, c);
        if (h) {
            if (this.removeFromArray_(h, f)) {
                this.getGridCellCreate_(e.x, e.y, c).push(f)
            }
        }
        if (c === this.mapZoom_) {
            if (this.isGridPointVisible_(d)) {
                if (!this.isGridPointVisible_(e)) {
                    this.removeOverlay_(f);
                    a = true
                }
            } else {
                if (this.isGridPointVisible_(e)) {
                    this.addOverlay_(f);
                    a = true
                }
            }
        }
        d.x = d.x >> 1;
        d.y = d.y >> 1;
        e.x = e.x >> 1;
        e.y = e.y >> 1;
        --c
    }
    if (a) {
        this.notifyListeners_()
    }
};
MarkerManager.prototype.removeMarker = function(d) {
    var b = this.maxZoom_;
    var a = false;
    var e = d.getPosition();
    var c = this.getTilePoint_(e, b, new google.maps.Size(0, 0, 0, 0));
    while (b >= 0) {
        var f = this.getGridCellNoCreate_(c.x, c.y, b);
        if (f) {
            this.removeFromArray_(f, d)
        }
        if (b === this.mapZoom_) {
            if (this.isGridPointVisible_(c)) {
                this.removeOverlay_(d);
                a = true
            }
        }
        c.x = c.x >> 1;
        c.y = c.y >> 1;
        --b
    }
    if (a) {
        this.notifyListeners_()
    }
    this.numMarkers_[d.MarkerManager_minZoom]--
};
MarkerManager.prototype.addMarkers = function(b, a, c) {
    var d = this.getOptMaxZoom_(c);
    for (var i = b.length - 1; i >= 0; i--) {
        this.addMarkerBatch_(b[i], a, d)
    }
    this.numMarkers_[a] += b.length
};
MarkerManager.prototype.getOptMaxZoom_ = function(a) {
    return a || this.maxZoom_
};
MarkerManager.prototype.getMarkerCount = function(a) {
    var b = 0;
    for (var z = 0; z <= a; z++) {
        b += this.numMarkers_[z]
    }
    return b
};
MarkerManager.prototype.getMarker = function(c, e, d) {
    var b = new google.maps.LatLng(c, e);
    var f = this.getTilePoint_(b, d, new google.maps.Size(0, 0, 0, 0));
    var g = new google.maps.Marker({
        position: b
    });
    var a = this.getGridCellNoCreate_(f.x, f.y, d);
    if (a !== undefined) {
        for (var i = 0; i < a.length; i++) {
            if (c === a[i].getPosition().lat() && e === a[i].getPosition().lng()) {
                g = a[i]
            }
        }
    }
    return g
};
MarkerManager.prototype.addMarker = function(d, a, b) {
    var e = this.getOptMaxZoom_(b);
    this.addMarkerBatch_(d, a, e);
    var c = this.getTilePoint_(d.getPosition(), this.mapZoom_, new google.maps.Size(0, 0, 0, 0));
    if (this.isGridPointVisible_(c) && a <= this.shownBounds_.z && this.shownBounds_.z <= e) {
        this.addOverlay_(d);
        this.notifyListeners_()
    }
    this.numMarkers_[a]++
};

function GridBounds(a) {
    this.minX = Math.min(a[0].x, a[1].x);
    this.maxX = Math.max(a[0].x, a[1].x);
    this.minY = Math.min(a[0].y, a[1].y);
    this.maxY = Math.max(a[0].y, a[1].y)
}
GridBounds.prototype.equals = function(a) {
    if (this.maxX === a.maxX && this.maxY === a.maxY && this.minX === a.minX && this.minY === a.minY) {
        return true
    } else {
        return false
    }
};
GridBounds.prototype.containsPoint = function(a) {
    var b = this;
    return (b.minX <= a.x && b.maxX >= a.x && b.minY <= a.y && b.maxY >= a.y)
};
MarkerManager.prototype.getGridCellCreate_ = function(x, y, z) {
    var b = this.grid_[z];
    if (x < 0) {
        x += this.gridWidth_[z]
    }
    var c = b[x];
    if (!c) {
        c = b[x] = [];
        return (c[y] = [])
    }
    var a = c[y];
    if (!a) {
        return (c[y] = [])
    }
    return a
};
MarkerManager.prototype.getGridCellNoCreate_ = function(x, y, z) {
    var a = this.grid_[z];
    if (x < 0) {
        x += this.gridWidth_[z]
    }
    var b = a[x];
    return b ? b[y] : undefined
};
MarkerManager.prototype.getGridBounds_ = function(j, b, c, e) {
    b = Math.min(b, this.maxZoom_);
    var i = j.getSouthWest();
    var f = j.getNorthEast();
    var d = this.getTilePoint_(i, b, c);
    var g = this.getTilePoint_(f, b, e);
    var a = this.gridWidth_[b];
    if (f.lng() < i.lng() || g.x < d.x) {
        d.x -= a
    }
    if (g.x - d.x + 1 >= a) {
        d.x = 0;
        g.x = a - 1
    }
    var h = new GridBounds([d, g]);
    h.z = b;
    return h
};
MarkerManager.prototype.getMapGridBounds_ = function() {
    return this.getGridBounds_(this.map_.getBounds(), this.mapZoom_, this.swPadding_, this.nePadding_)
};
MarkerManager.prototype.onMapMoveEnd_ = function() {
    this.objectSetTimeout_(this, this.updateMarkers_, 0)
};
MarkerManager.prototype.objectSetTimeout_ = function(b, a, c) {
    return window.setTimeout(function() {
        a.call(b)
    }, c)
};
MarkerManager.prototype.visible = function() {
    return this.show_ ? true : false
};
MarkerManager.prototype.isHidden = function() {
    return !this.show_
};
MarkerManager.prototype.show = function() {
    this.show_ = true;
    this.refresh()
};
MarkerManager.prototype.hide = function() {
    this.show_ = false;
    this.refresh()
};
MarkerManager.prototype.toggle = function() {
    this.show_ = !this.show_;
    this.refresh()
};
MarkerManager.prototype.refresh = function() {
    if (this.shownMarkers_ > 0) {
        this.processAll_(this.shownBounds_, this.removeOverlay_)
    }
    if (this.show_) {
        this.processAll_(this.shownBounds_, this.addOverlay_)
    }
    this.notifyListeners_()
};
MarkerManager.prototype.updateMarkers_ = function() {
    this.mapZoom_ = this.map_.getZoom();
    var a = this.getMapGridBounds_();
    if (a.equals(this.shownBounds_) && a.z === this.shownBounds_.z) {
        return
    }
    if (a.z !== this.shownBounds_.z) {
        this.processAll_(this.shownBounds_, this.removeOverlay_);
        if (this.show_) {
            this.processAll_(a, this.addOverlay_)
        }
    } else {
        this.rectangleDiff_(this.shownBounds_, a, this.removeCellMarkers_);
        if (this.show_) {
            this.rectangleDiff_(a, this.shownBounds_, this.addCellMarkers_)
        }
    }
    this.shownBounds_ = a;
    this.notifyListeners_()
};
MarkerManager.prototype.notifyListeners_ = function() {
    google.maps.event.trigger(this, 'changed', this.shownBounds_, this.shownMarkers_)
};
MarkerManager.prototype.processAll_ = function(b, a) {
    for (var x = b.minX; x <= b.maxX; x++) {
        for (var y = b.minY; y <= b.maxY; y++) {
            this.processCellMarkers_(x, y, b.z, a)
        }
    }
};
MarkerManager.prototype.processCellMarkers_ = function(x, y, z, a) {
    var b = this.getGridCellNoCreate_(x, y, z);
    if (b) {
        for (var i = b.length - 1; i >= 0; i--) {
            a(b[i])
        }
    }
};
MarkerManager.prototype.removeCellMarkers_ = function(x, y, z) {
    this.processCellMarkers_(x, y, z, this.removeOverlay_)
};
MarkerManager.prototype.addCellMarkers_ = function(x, y, z) {
    this.processCellMarkers_(x, y, z, this.addOverlay_)
};
MarkerManager.prototype.rectangleDiff_ = function(c, d, a) {
    var b = this;
    b.rectangleDiffCoords_(c, d, function(x, y) {
        a.apply(b, [x, y, c.z])
    })
};
MarkerManager.prototype.rectangleDiffCoords_ = function(j, k, b) {
    var f = j.minX;
    var a = j.minY;
    var d = j.maxX;
    var h = j.maxY;
    var g = k.minX;
    var c = k.minY;
    var e = k.maxX;
    var i = k.maxY;
    var x, y;
    for (x = f; x <= d; x++) {
        for (y = a; y <= h && y < c; y++) {
            b(x, y)
        }
        for (y = Math.max(i + 1, a); y <= h; y++) {
            b(x, y)
        }
    }
    for (y = Math.max(a, c); y <= Math.min(h, i); y++) {
        for (x = Math.min(d + 1, g) - 1; x >= f; x--) {
            b(x, y)
        }
        for (x = Math.max(f, e + 1); x <= d; x++) {
            b(x, y)
        }
    }
};
MarkerManager.prototype.removeFromArray_ = function(a, c, b) {
    var d = 0;
    for (var i = 0; i < a.length; ++i) {
        if (a[i] === c || (b && a[i] === c)) {
            a.splice(i--, 1);
            d++
        }
    }
    return d
};

function ProjectionHelperOverlay(b) {
    this.setMap(b);
    var d = 8;
    var c = 1 << d;
    var a = 7;
    this._map = b;
    this._zoom = -1;
    this._X0 = this._Y0 = this._X1 = this._Y1 = -1
}
ProjectionHelperOverlay.prototype = new google.maps.OverlayView();
ProjectionHelperOverlay.prototype.LngToX_ = function(a) {
    return (1 + a / 180)
};
ProjectionHelperOverlay.prototype.LatToY_ = function(b) {
    var a = Math.sin(b * Math.PI / 180);
    return (1 - 0.5 / Math.PI * Math.log((1 + a) / (1 - a)))
};
ProjectionHelperOverlay.prototype.LatLngToPixel = function(a, d) {
    var c = this._map;
    var b = this.getProjection().fromLatLngToDivPixel(a);
    var e = {
        x: ~~(0.5 + this.LngToX_(a.lng()) * (2 << (d + 6))),
        y: ~~(0.5 + this.LatToY_(a.lat()) * (2 << (d + 6)))
    };
    return e
};
ProjectionHelperOverlay.prototype.draw = function() {
    if (!this.ready) {
        this.ready = true;
        google.maps.event.trigger(this, 'ready')
    }
};
