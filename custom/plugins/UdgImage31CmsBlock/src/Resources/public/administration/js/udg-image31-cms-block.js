(this.webpackJsonp=this.webpackJsonp||[]).push([["udg-image31-cms-block"],{"8a8g":function(e,i,s){},IJyB:function(e,i,s){var a=s("iyUG");"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);(0,s("SZ7m").default)("41dd9ba2",a,!0,{})},IaR7:function(e,i){e.exports='{% block sw_cms_block_image_31_preview  %}\n    <div class="sw-cms-preview-image-31">\n        <div class="sw-cms-preview-image-31__left">\n            <div class="sw-cms-preview-image-31__image">\n                <img :src="\'/administration/static/img/cms/preview_camera_small.jpg\' | asset">\n            </div>\n        </div>\n        <div class="sw-cms-preview-image-31__right">\n            <div class="sw-cms-preview-image-31__image">\n                <img :src="\'/administration/static/img/cms/preview_glasses_small.jpg\' | asset">\n            </div>\n        </div>\n    </div>\n{% endblock %}\n'},OYDj:function(e,i,s){"use strict";s.r(i);var a=s("QZEI"),t=s.n(a);s("IJyB");const{Component:c}=Shopware;c.register("sw-cms-block-image-31",{template:t.a});var m=s("IaR7"),n=s.n(m);s("Wz9I");const{Component:o}=Shopware;o.register("sw-cms-preview-image-31",{template:n.a}),Shopware.Service("cmsService").registerCmsBlock({name:"image-31",label:"sw-cms.blocks.ImageCMSBlock.Image31.label",category:"image",component:"sw-cms-block-image-31",previewComponent:"sw-cms-preview-image-31",defaultConfig:{marginBottom:"20px",marginTop:"20px",marginLeft:"20px",marginRight:"20px",sizingMode:"boxed"},slots:{left:{type:"image",default:{config:{displayMode:{source:"static",value:"cover"}},data:{media:{url:"/administration/static/img/cms/preview_camera_large.jpg"}}}},right:{type:"image",default:{config:{displayMode:{source:"static",value:"cover"}},data:{media:{url:"/administration/static/img/cms/preview_glasses_large.jpg"}}}}}})},QZEI:function(e,i){e.exports='{% block sw_cms_block_image_31 %}\n    <div class="sw-cms-block-image-31">\n        <div class="sw-cms-block-image-31__left">\n            <slot name="left"></slot>\n        </div>\n        <div class="sw-cms-block-image-31__right">\n            <slot name="right"></slot>\n        </div>\n    </div>\n{% endblock %}\n'},Wz9I:function(e,i,s){var a=s("8a8g");"string"==typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);(0,s("SZ7m").default)("552d0ded",a,!0,{})},iyUG:function(e,i,s){}},[["OYDj","runtime","vendors-node"]]]);