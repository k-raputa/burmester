(this.webpackJsonp=this.webpackJsonp||[]).push([["udg-storefinder"],{"+RH2":function(e,t,n){"use strict";n.r(t);var i=n("5gD/"),o=n.n(i);n("EeXp");const{Component:r,Mixin:s}=Shopware;r.register("sw-cms-el-storefinder",{template:o.a,mixins:[s.getByName("cms-element")],computed:{mapSrc:()=>"",mapApiKey:()=>"",mapWidth:()=>"",mapHeight:()=>""},created(){this.createdComponent()},methods:{createdComponent(){this.initElementConfig("storefinder")}}});var a=n("A4xo"),l=n.n(a);n("0/iY");const{Component:d,Mixin:c}=Shopware;d.register("sw-cms-el-config-storefinder",{template:l.a,mixins:[c.getByName("cms-element")],created(){this.createdComponent()},methods:{createdComponent(){this.initElementConfig("storefinder")}}});var u=n("lnp+"),m=n.n(u);n("/tQy");const{Component:p}=Shopware;p.register("sw-cms-el-preview-storefinder",{template:m.a});var g=n("BMwp"),f=n("50gM");Shopware.Locale.extend("de-DE",g),Shopware.Locale.extend("en-GB",f),Shopware.Service("cmsService").registerCmsElement({name:"storefinder",label:"sw-cms.elements.udg.storefinder.general.label",component:"sw-cms-el-storefinder",configComponent:"sw-cms-el-config-storefinder",previewComponent:"sw-cms-el-preview-storefinder",defaultConfig:{mapSrc:{source:"static",value:""},mapApiKey:{source:"static",value:""},mapWidth:{source:"static",value:"100%"},mapHeight:{source:"static",value:"100%"}}});var h=n("fWvn"),w=n.n(h);const{Component:b,Mixin:y}=Shopware,{Criteria:v}=Shopware.Data;b.register("udg-storefinder-list",{template:w.a,inject:["repositoryFactory"],mixins:[y.getByName("listing")],data(){return{isLoading:!1,criteria:null,repository:null,entities:null,total:null,term:this.$route.query?this.$route.query.term:null}},metaInfo(){return{title:this.$createTitle()}},computed:{columns(){return[{property:"company",dataIndex:"company",label:this.$tc("udg.storefinder.module.column.company"),routerLink:"udg.storefinder.detail",inlineEdit:"string",allowResize:!0,primary:!0},{property:"street",dataIndex:"street",label:this.$tc("udg.storefinder.module.column.street"),inlineEdit:"string",allowResize:!0},{property:"location",dataIndex:"location",label:this.$tc("udg.storefinder.module.column.location"),inlineEdit:"string",allowResize:!0},{property:"country.name",dataIndex:"country.name",label:this.$tc("udg.storefinder.module.column.country"),allowResize:!0},{property:"phone",dataIndex:"phone",label:this.$tc("udg.storefinder.module.column.phone"),inlineEdit:"string",allowResize:!0},{property:"email",dataIndex:"email",label:this.$tc("udg.storefinder.module.column.email"),inlineEdit:"string",allowResize:!0},{property:"web",dataIndex:"web",label:this.$tc("udg.storefinder.module.column.web"),inlineEdit:"string",allowResize:!0},{property:"active",dataIndex:"active",label:this.$tc("udg.storefinder.module.column.active"),inlineEdit:"bool",allowResize:!0}]}},created(){this.createdComponent()},methods:{onChangeLanguage(e){this.createdComponent()},createdComponent(){this.repository=this.repositoryFactory.create("udg_storefinder"),this.criteria=new v,this.criteria.addSorting(v.sort("company","ASC")),this.criteria.addSorting(v.sort("createdAt","ASC")),this.criteria.addAssociation("country"),this.term&&this.criteria.setTerm(this.term),this.isLoading=!0;const e={...Shopware.Context.api,inheritance:!0};this.repository.search(this.criteria,e).then(e=>{this.entities=e,this.total=e.total,this.isLoading=!1})},onSearch(e){this.criteria.setTerm(e),this.$route.query.term=e,this.$refs.listing.doSearch()}}});var _=n("6BSd"),S=n.n(_);const{Component:k,Mixin:x}=Shopware;k.register("udg-storefinder-detail",{template:S.a,inject:["repositoryFactory"],mixins:[x.getByName("notification"),x.getByName("placeholder")],metaInfo(){return{title:this.$createTitle()}},data:()=>({entity:null,isLoading:!1,processSuccess:!1,repository:null,country:null}),created(){this.repository=this.repositoryFactory.create("udg_storefinder"),this.getEntity()},computed:{countryRepository(){return this.repositoryFactory.create("country")},country:{get(){return this.entity.country},set(e){return this.entity.country=country}}},methods:{getEntity(){this.repository.get(this.$route.params.id,Shopware.Context.api).then(e=>{this.entity=e})},saveOnLanguageChange(){return this.onClickSave()},abortOnLanguageChange(){return this.repository.hasChanges(this.entity)},onChangeLanguage(){this.getEntity()},onClickSave(){this.isLoading=!0,this.repository.save(this.entity,Shopware.Context.api).then(()=>{this.getEntity(),this.isLoading=!1,this.processSuccess=!0}).catch(e=>{this.isLoading=!1,this.createNotificationError({title:this.$tc("udg.storefinder.module.page.detail.error"),message:e})})},saveFinish(){this.processSuccess=!1}}});n("IaPl");var L=n("zBqR"),$=n("KVBz");const{Module:C}=Shopware;C.register("udg-storefinder",{type:"plugin",name:"udg.storefinder.module.general.label",title:"udg.storefinder.module.general.title",description:"udg.storefinder.module.general.description",version:"1.0.0",targetVersion:"1.0.0",color:"#ff68b4",icon:"default-object-lab-flask",entity:"udg_storefinder",snippets:{"de-DE":L,"en-GB":$},routes:{index:{components:{default:"udg-storefinder-list"},path:"index"},detail:{components:{default:"udg-storefinder-detail"},path:"detail/:id",meta:{parentPath:"udg.storefinder.index"}},create:{components:{default:"udg-storefinder-create"},path:"create",meta:{parentPath:"udg.storefinder.index"}}},navigation:[{id:"udg-storefinder",label:"udg.storefinder.module.general.navigation",color:"#ff68b4",parent:"sw-content",path:"udg.storefinder.index",icon:"default-object-lab-flask",position:100}]})},"/tQy":function(e,t,n){var i=n("Fx/s");"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);(0,n("SZ7m").default)("7753276b",i,!0,{})},"0/iY":function(e,t,n){var i=n("KlXN");"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);(0,n("SZ7m").default)("6f5f9859",i,!0,{})},"50gM":function(e){e.exports=JSON.parse('{"sw-cms":{"elements":{"udg":{"storefinder":{"general":{"label":"Google Maps Store Finder Element"},"config":{"description":"Feel free to customize the look and feel of your Location Finder","mapUrl":{"label":"Map URL","placeholder":"Insert maps url here..."},"mapApiKey":{"label":"API Key","placeholder":"Insert api key here..."},"mapWidth":{"label":"Map width","placeholder":"Insert maps width here..."},"mapHeight":{"label":"Map height","placeholder":"Insert maps height here..."}}}}}}}')},"5gD/":function(e,t){e.exports='{% block sw_cms_el_storefinder %}\n    <div class="sw-cms-el sw-cms-el-storefinder">\n        <div class="sw-cms-el-storefinder-map">\n            <img :src="\'/udgstorefinder/administration/static/img/cms/preview-maps.png\' | asset">\n        </div>\n    </div>\n{% endblock %}\n'},"6BSd":function(e,t){e.exports='{% block udg_storefinder_detail %}\n    <sw-page class="udg-module-storefinder-detail">\n        {% block udg_storefinder_detail_header %}\n            <template slot="smart-bar-header">\n                <h2>{{ placeholder(entity, \'city\', $tc(\'udg.storefinder.module.page.detail.default\')) }}</h2>\n            </template>\n        {% endblock %}\n\n        {% block udg_storefinder_detail_language_switch %}\n            <template slot="language-switch">\n                <sw-language-switch\n                    :saveChangesFunction="saveOnLanguageChange"\n                    :abortChangeFunction="abortOnLanguageChange"\n                    @on-change="onChangeLanguage">\n                </sw-language-switch>\n            </template>\n        {% endblock %}\n\n        <template slot="smart-bar-actions">\n            <sw-button :routerLink="{ name: \'udg.storefinder.index\' }">\n                {{ $tc(\'udg.storefinder.module.button.cancel\') }}\n            </sw-button>\n            <sw-button-process :isLoading="isLoading"\n                               :processSuccess="processSuccess"\n                               variant="primary"\n                               @process-finish="saveFinish"\n                               @click="onClickSave">\n                {{ $tc(\'udg.storefinder.module.button.save\') }}\n            </sw-button-process>\n        </template>\n\n        <template slot="content">\n            <sw-card-view>\n                <sw-card v-if="entity" :title="$tc(\'udg.storefinder.module.page.detail.card.address\')"\n                         :isLoading="isLoading">\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.company\')"\n                              v-model="entity.company"\n                              required\n                              validation="required">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.street\')"\n                              v-model="entity.street"\n                              required\n                              validation="required">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.location\')"\n                              v-model="entity.location"\n                              required\n                              validation="required">\n                    </sw-field>\n                    {% block sw_customer_address_form_country_field %}\n                        <sw-entity-single-select\n                            class="udg-module-storefinder-country-select"\n                            entity="country"\n                            :label="$tc(\'udg.storefinder.module.column.country\')"\n                            validation="required"\n                            required\n                            v-model="entity.countryId">\n                        </sw-entity-single-select>\n                    {% endblock %}\n                </sw-card>\n\n                <sw-card v-if="entity" :title="$tc(\'udg.storefinder.module.page.detail.card.contact\')"\n                         :isLoading="isLoading">\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.phone\')"\n                              v-model="entity.phone">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.email\')"\n                              v-model="entity.email">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.web\')"\n                              v-model="entity.web">\n                    </sw-field>\n                </sw-card>\n\n                <sw-card v-if="entity" :title="$tc(\'udg.storefinder.module.page.detail.card.technical\')"\n                         :isLoading="isLoading">\n                    {% block sw_review_detail_description_list_status %}\n                        <sw-switch-field\n                            :label="$tc(\'udg.storefinder.module.column.active\')"\n                            class="status-switch"\n                            :bordered="true"\n                            v-model="entity.active">\n                        </sw-switch-field>\n                    {% endblock %}\n\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.latitude\')"\n                              v-model="entity.latitude"\n                              required\n                              validation="required">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.longitude\')"\n                              v-model="entity.longitude"\n                              required\n                              validation="required">\n                    </sw-field>\n                    <sw-field :label="$tc(\'udg.storefinder.module.column.distrotype\')"\n                              v-model="entity.distrotype">\n                    </sw-field>\n                    <sw-textarea-field type="textarea"\n                                       :label="$tc(\'udg.storefinder.module.column.productline\')"\n                                       v-model="entity.productline">\n                    </sw-textarea-field>\n                </sw-card>\n            </sw-card-view>\n        </template>\n    </sw-page>\n{% endblock %}\n'},A4xo:function(e,t){e.exports='{% block sw_cms_el_config_storefinder %}\n<div class="sw-cms-el-config-storefinder">\n    <div class="sw-cms-el-config-storefinder-headline">\n        <p>{{ $tc(\'sw-cms.elements.udg.storefinder.config.description\') }}</p>\n    </div>\n\n    <sw-field class="sw-cms-el-config-storefinder-field"\n              v-model="element.config.mapSrc.value"\n              type="text"\n              :label="$tc(\'sw-cms.elements.udg.storefinder.config.mapUrl.label\')"\n              :placeholder="$tc(\'sw-cms.elements.udg.storefinder.config.mapUrl.placeholder\')">\n    </sw-field>\n\n    <sw-field class="sw-cms-el-config-storefinder-field"\n              v-model="element.config.mapApiKey.value"\n              type="text"\n              :label="$tc(\'sw-cms.elements.udg.storefinder.config.mapApiKey.label\')"\n              :placeholder="$tc(\'sw-cms.elements.udg.storefinder.config.mapApiKey.placeholder\')">\n    </sw-field>\n\n    <sw-field class="sw-cms-el-config-storefinder-field"\n              v-model="element.config.mapWidth.value"\n              type="text"\n              :label="$tc(\'sw-cms.elements.udg.storefinder.config.mapWidth.label\')"\n              :placeholder="$tc(\'sw-cms.elements.udg.storefinder.config.mapWidth.placeholder\')">\n    </sw-field>\n\n    <sw-field class="sw-cms-el-config-storefinder-field"\n              v-model="element.config.mapHeight.value"\n              type="text"\n              :label="$tc(\'sw-cms.elements.udg.storefinder.config.mapHeight.label\')"\n              :placeholder="$tc(\'sw-cms.elements.udg.storefinder.config.mapHeight.placeholder\')">\n    </sw-field>\n</div>\n{% endblock %}\n'},BMwp:function(e){e.exports=JSON.parse('{"sw-cms":{"elements":{"udg":{"storefinder":{"general":{"label":"Google Maps Store Finder Element"},"config":{"description":"Nur zu, passen Sie die Karte an","mapUrl":{"label":"Karten-URL","placeholder":"Geben Sie hier die URL ein..."},"mapApiKey":{"label":"API-Schlüssel","placeholder":"Geben Sie hier den API-Schlüssel an..."},"mapWidth":{"label":"Breite der Karte","placeholder":"Geben Sie hier die Breite an..."},"mapHeight":{"label":"Höhe der Karte","placeholder":"Geben Sie hier die Höhe an..."}}}}}}}')},EeXp:function(e,t,n){var i=n("ZoK4");"string"==typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);(0,n("SZ7m").default)("aa702b94",i,!0,{})},"Fx/s":function(e,t,n){},IaPl:function(e,t){Shopware.Component.extend("udg-storefinder-create","udg-storefinder-detail",{methods:{getEntity(){this.entity=this.repository.create(Shopware.Context.api)},onClickSave(){this.isLoading=!0,this.repository.save(this.entity,Shopware.Context.api).then(()=>{this.isLoading=!1,this.$router.push({name:"udg.storefinder.detail",params:{id:this.entity.id}})}).catch(e=>{this.isLoading=!1,this.createNotificationError({title:"errors on create",message:e})})}}})},KVBz:function(e){e.exports=JSON.parse('{"udg":{"storefinder":{"module":{"general":{"label":"Google Maps Storefinder Module","title":"Storefinder Module","navigation":"Storefinder","description":"Manage store locations"},"button":{"new":"New Locaction","cancel":"Cancel","save":"Save Locaction"},"page":{"list":{"title":"Storefinder locations","noData":"No storefinder locations yet","noDataSubline":"Manage storefinder locations here","searchBarPlaceholder":"Find Storefinder locations..."},"detail":{"default":"Your location","error":"Error :(","card":{"address":"Address data","contact":"Contact data","technical":"Technical data"}}},"column":{"country":"Country","latitude":"Latitude","longitude":"Longitude","distrotype":"Distributor type","productline":"Product lines","active":"Active","company":"Company name","street":"Street and number","location":"Location","phone":"Phone number","email":"Email address","web":"Homepage"}}}}}')},KlXN:function(e,t,n){},ZoK4:function(e,t,n){},fWvn:function(e,t){e.exports='{% block udg_storefinder_list %}\n    <sw-page class="udg-module-storefinder-list">\n        {% block udg_storefinder_list_search_bar %}\n            <template slot="search-bar">\n                <sw-search-bar initialSearchType="udg_storefinder"\n                               :initialSearch="term"\n                               :placeholder="$tc(\'udg.storefinder.module.page.list.searchBarPlaceholder\')"\n                               @search="onSearch">\n                </sw-search-bar>\n            </template>\n        {% endblock %}\n\n        {% block udg_storefinder_list_smart_bar_header %}\n            <template slot="smart-bar-header">\n                {% block udg_storefinder_list_smart_bar_header_title %}\n                    <h2>\n                        {% block udg_storefinder_list_smart_bar_header_title_text %}\n                            {{ $tc(\'udg.storefinder.module.page.list.title\') }}\n                        {% endblock %}\n\n                        {% block udg_storefinder_list_smart_bar_header_amount %}\n                            <span v-if="!isLoading && total" class="sw-page__smart-bar-amount">\n                                ({{ total }})\n                            </span>\n                        {% endblock %}\n                    </h2>\n                {% endblock %}\n            </template>\n        {% endblock %}\n\n        {% block udg_storefinder_list_actions %}\n            <template slot="smart-bar-actions">\n                {% block udg_storefinder_list_smart_bar_actions %}\n                    <sw-button :routerLink="{ name: \'udg.storefinder.create\' }" variant="primary">\n                        {{ $tc(\'udg.storefinder.module.button.new\') }}\n                    </sw-button>\n                {% endblock %}\n            </template>\n        {% endblock %}\n\n        {% block udg_storefinder_list_language_switch %}\n            <template slot="language-switch">\n                <sw-language-switch @on-change="onChangeLanguage"></sw-language-switch>\n            </template>\n        {% endblock %}\n\n        <template slot="content">\n            {% block udg_storefinder_list_content %}\n                <sw-entity-listing ref="listing"\n                                   v-if="entities"\n                                   :items="entities"\n                                   :repository="repository"\n                                   :showSelection="false"\n                                   :columns="columns"\n                                   :showSelection="true"\n                                   detailRoute="udg.storefinder.detail"\n                                   identifier="udg-storefinder-list">\n                </sw-entity-listing>\n            {% endblock %}\n\n            {% block udg_storefinder_list_grid_loader %}\n                <sw-loader v-if="isLoading"></sw-loader>\n            {% endblock %}\n\n            {% block udg_storefinder_list_empty_state %}\n                <sw-empty-state v-if="!isLoading && !total"\n                                :title="$tc(\'udg.storefinder.module.page.list.noData\')"\n                                icon="default-documentation-file"\n                                :subline="$tc(\'udg.storefinder.module.page.list.noDataSubline\')">\n                </sw-empty-state>\n            {% endblock %}\n        </template>\n    </sw-page>\n{% endblock %}\n'},"lnp+":function(e,t){e.exports='{% block sw_cms_el_storefinder_preview %}\n    <div class="sw-cms-el-preview-storefinder">\n        <img :src="\'/udgstorefinder/administration/static/img/cms/preview-maps.png\' | asset">\n    </div>\n{% endblock %}\n'},zBqR:function(e){e.exports=JSON.parse('{"udg":{"storefinder":{"module":{"general":{"label":"Google Maps Storefinder Modul","title":"Storefinder Modul","navigation":"Storefinder","description":"Storefinder-Locations verwalten"},"button":{"new":"Neue Locaction","cancel":"Abbrechen","save":"Locaction speichern"},"page":{"list":{"title":"Storefinder-Locations","noData":"Derzeit sind keine Locations vorhanden","noDataSubline":"Storefinder-Locations hier verwalten","searchBarPlaceholder":"Storefinder-Locations suchen..."},"detail":{"default":"Ihre Location","error":"Fehler :(","card":{"address":"Adressdaten","contact":"Kontaktdaten","technical":"Technische Daten"}}},"column":{"country":"Länderkürzel","latitude":"Geogr. Länge","longitude":"Geogr. Breite","distrotype":"Distributor-Typ","productline":"Produktlinien","active":"Aktiviert","company":"Name des Unternehmens","street":"Straße und Hausnur.","location":"PLZ und Ort","phone":"Telefon","email":"Email-Adresse","web":"Homepage"}}}}}')}},[["+RH2","runtime","vendors-node"]]]);