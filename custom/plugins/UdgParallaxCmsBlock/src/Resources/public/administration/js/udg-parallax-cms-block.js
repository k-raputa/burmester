(this.webpackJsonp=this.webpackJsonp||[]).push([["udg-parallax-cms-block"],{"9xtn":function(a,s,e){},AAvL:function(a,s,e){var l=e("JkGR");"string"==typeof l&&(l=[[a.i,l,""]]),l.locals&&(a.exports=l.locals);(0,e("SZ7m").default)("694b8e9b",l,!0,{})},JkGR:function(a,s,e){},b0KX:function(a,s,e){var l=e("9xtn");"string"==typeof l&&(l=[[a.i,l,""]]),l.locals&&(a.exports=l.locals);(0,e("SZ7m").default)("cb8c0eb4",l,!0,{})},gc5U:function(a,s){a.exports='{% block sw_cms_block_parallax_preview  %}\n    <div class="sw-cms-preview-parallax">\n        <div class="sw-cms-preview-parallax__left">\n            <div class="sw-cms-preview-parallax__image">\n                <img :src="\'/administration/static/img/cms/preview_camera_small.jpg\' | asset">\n            </div>\n        </div>\n        <div class="sw-cms-preview-parallax__right">\n            <div class="sw-cms-preview-parallax__image">\n                <img :src="\'/administration/static/img/cms/preview_glasses_small.jpg\' | asset">\n            </div>\n        </div>\n    </div>\n{% endblock %}\n'},hbgV:function(a,s,e){"use strict";e.r(s);var l=e("jDpj"),i=e.n(l);e("AAvL");const{Component:t}=Shopware;t.register("sw-cms-block-parallax",{template:i.a});var c=e("gc5U"),n=e.n(c);e("b0KX");const{Component:r}=Shopware;r.register("sw-cms-preview-parallax",{template:n.a}),Shopware.Service("cmsService").registerCmsBlock({name:"parallax",label:"sw-cms.blocks.TextImageCMSBlock.Parallax.label",category:"text-image",component:"sw-cms-block-parallax",previewComponent:"sw-cms-preview-parallax",defaultConfig:{marginBottom:"20px",marginTop:"20px",marginLeft:"20px",marginRight:"20px",sizingMode:"boxed"},slots:{left:{type:"image",default:{config:{displayMode:{source:"static",value:"cover"}},data:{media:{url:"/administration/static/img/cms/preview_camera_large.jpg"}}}},right:{type:"image",default:{config:{displayMode:{source:"static",value:"cover"}},data:{media:{url:"/administration/static/img/cms/preview_glasses_large.jpg"}}}}}})},jDpj:function(a,s){a.exports='{% block sw_cms_block_parallax %}\n    <div class="sw-cms-block-parallax">\n        <div class="sw-cms-block-parallax__left">\n            <slot name="left"></slot>\n        </div>\n        <div class="sw-cms-block-parallax__right">\n            <slot name="right"></slot>\n        </div>\n    </div>\n{% endblock %}\n'}},[["hbgV","runtime","vendors-node"]]]);