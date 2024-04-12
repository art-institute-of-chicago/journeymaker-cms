(function(){"use strict";var t={4028:function(t,e,s){var i=s(9016),n=s(7176),a=s(4540),c=(s(3860),s(9824)),r=s(7124),o=function(){var t=this,e=t._self._c;return e("div",{staticClass:"buckets"},[e("div",{staticClass:"buckets__page-title"},[e("div",{staticClass:"container buckets__page-title-content"},[e("h2",[t._t("default")],2),e("div",{staticClass:"buckets__page-title-actions"},[e("a17-button",{attrs:{variant:"validate"},on:{click:t.save}},[t._v(t._s(t.$trans("buckets.publish")))]),t._l(t.extraActions,(function(s){return e("a17-button",{key:s.url,attrs:{el:"a",href:s.url,download:s.download||"",target:s.target||"",rel:s.rel||"",variant:"secondary"}},[t._v(t._s(s.label))])}))],2)])]),e("div",{staticClass:"container"},[e("div",{staticClass:"wrapper"},[e("div",{staticClass:"buckets__container col--even"},[e("a17-fieldset",{staticClass:"buckets__fieldset",attrs:{title:t.title,activeToggle:!1}},[e("div",{staticClass:"buckets__header"},[e("div",{staticClass:"buckets__sources"},[t.singleSource?t._e():e("a17-vselect",{staticClass:"sources__select",attrs:{name:"sources",selected:t.currentSource,options:t.dataSources,required:!0},on:{change:t.changeDataSource}})],1),e("div",{staticClass:"buckets__filter"},[e("a17-filter",{on:{submit:t.filterBucketsData}})],1)]),t.source.items.length>0?e("table",{staticClass:"buckets__list"},[e("tbody",t._l(t.source.items,(function(s){return e("a17-bucket-item-source",{key:s.id,attrs:{item:s,singleBucket:t.singleBucket,buckets:t.buckets},on:{"add-to-bucket":t.addToBucket}})})),1)]):e("div",{staticClass:"buckets__empty"},[e("h4",[t._v(t._s(t.emptySource))])]),e("a17-paginate",{attrs:{max:t.max,value:t.page,offset:t.offset,availableOffsets:t.availableOffsets},on:{changePage:t.updatePage,changeOffset:t.updateOffset}})],1)],1),e("div",{staticClass:"buckets__container col--even"},t._l(t.buckets,(function(s,i){return e("a17-fieldset",{key:s.id,class:"buckets__fieldset buckets__fieldset--"+(i+1),attrs:{name:"bucket_"+s.id,activeToggle:!1}},[e("h3",{staticClass:"buckets__fieldset__header",attrs:{slot:"header"},slot:"header"},[e("span",[t.buckets.length>1?e("span",{staticClass:"buckets__number"},[t._v(t._s(i+1))]):t._e(),t._v(" "+t._s(s.name))]),t._v(" "),e("span",{staticClass:"buckets__size-infos"},[t._v(t._s(s.children.length)+" / "+t._s(s.max))])]),s.children.length>0?e("draggable",t._b({staticClass:"buckets__list buckets__draggable",attrs:{value:s.children,tag:"table"},on:{change:function(e){return t.sortBucket(e,i)}}},"draggable",t.dragOptions,!1),[e("transition-group",{attrs:{name:"fade_scale_list",tag:"tbody"}},t._l(s.children,(function(i,n){return e("a17-bucket-item",{key:"".concat(i.id,"_").concat(n),attrs:{item:i,restricted:t.restricted,draggable:s.children.length>1,singleBucket:t.singleBucket,singleSource:t.singleSource,bucket:s.id,buckets:t.buckets,withToggleFeatured:s.withToggleFeatured,toggleFeaturedLabels:s.toggleFeaturedLabels},on:{"add-to-bucket":t.addToBucket,"remove-from-bucket":t.deleteFromBucket,"toggle-featured-in-bucket":t.toggleFeaturedInBucket}})})),1)],1):e("div",{staticClass:"buckets__empty"},[e("h4",[t._v(t._s(t.emptyBuckets))])])],1)})),1)])]),e("a17-modal",{ref:"overrideBucket",staticClass:"modal--tiny modal--form modal--withintro",attrs:{title:"Override Bucket"}},[e("p",{staticClass:"modal--tiny-title"},[e("strong",[t._v("Are you sure ?")])]),e("p",{domProps:{innerHTML:t._s(t.overrideBucketText)}}),e("a17-inputframe",[e("a17-button",{attrs:{variant:"validate"},on:{click:t.override}},[t._v("Override")]),e("a17-button",{attrs:{variant:"aslink"},on:{click:function(e){return t.$refs.overrideBucket.close()}}},[e("span",[t._v("Cancel")])])],1)],1)],1)},u=[],l=s(7052),d=s.n(l),_=s(6036),m=s(3820),f=s(892),b=s(7980),k=s(6216),g=s(9684),p=s(7632),h=s(624),v=function(){var t=this,e=t._self._c;return e("tr",{staticClass:"buckets__item",class:t.customClasses},[t.draggable?e("td",{staticClass:"drag__handle"},[e("div",{staticClass:"drag__handle--drag"})]):t._e(),t.item.thumbnail?e("td",{staticClass:"buckets__itemThumbnail"},[e("img",{attrs:{src:t.item.thumbnail,alt:t.item.name}})]):t._e(),t.withToggleFeatured?e("td",{staticClass:"buckets__itemStarred",class:{"buckets__itemStarred--active":t.item.starred}},[e("span",{directives:[{name:"tooltip",rawName:"v-tooltip"}],attrs:{"data-tooltip-title":t.item.starred?t.toggleFeaturedLabels["unstar"]?t.toggleFeaturedLabels["unstar"]:"Unfeature":t.toggleFeaturedLabels["star"]?t.toggleFeaturedLabels["star"]:"Feature"},on:{click:function(e){return e.preventDefault(),t.toggleFeatured.apply(null,arguments)}}},[e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"star-feature_active"}}),e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"star-feature"}})])]):t._e(),e("td",{staticClass:"buckets__itemTitle"},[e("h4",[t.item.edit?e("span",{staticClass:"f--link-underlined--o"},[e("a",{attrs:{href:t.item.edit,target:"_blank"}},[t._v(t._s(t.item.name))])]):e("span",[t._v(t._s(t.item.name))])])]),t.item.content_type&&!t.singleSource?e("td",{staticClass:"buckets__itemContentType"},[t._v(" "+t._s(t.item.content_type.label)+" ")]):t._e(),e("td",{staticClass:"buckets__itemOptions"},[t.singleBucket?t._e():e("a17-dropdown",{ref:"bucketDropdown",staticClass:"item__dropdown bucket__action",attrs:{position:"bottom-right",title:"Featured in",clickable:!0}},[e("a17-button",{attrs:{variant:"icon"},on:{click:function(e){return t.$refs.bucketDropdown.toggle()}}},[e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"more-dots"}})]),t.restricted?e("div",{staticClass:"item__dropdown__content",attrs:{slot:"dropdown__content"},slot:"dropdown__content"},[e("a17-radiogroup",{attrs:{name:"bucketsSelection",radioClass:"bucket",radios:t.dropDownBuckets,initialValue:t.selectedBuckets()[0]},on:{change:t.updateBucket}})],1):e("div",{staticClass:"item__dropdown__content",attrs:{slot:"dropdown__content"},slot:"dropdown__content"},[e("a17-checkboxgroup",{attrs:{name:"bucketsSelection",options:t.dropDownBuckets,selected:t.selectedBuckets()},on:{change:t.updateBucket}})],1)],1),e("a17-button",{staticClass:"bucket__action",attrs:{icon:"close"},on:{click:function(e){return t.removeFromBucket()}}},[e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"close_icon"}})])],1)])},T=[],E=s(4064),B=s(3032),y={components:{A17Dropdown:B.c},name:"a17BucketItem",props:{bucket:{type:String},draggable:{type:Boolean,default:!1},restricted:{type:Boolean,default:!1},type:{type:String,default:"bucket"},singleSource:{type:Boolean,default:!1},withToggleFeatured:{type:Boolean,default:!1},toggleFeaturedLabels:{type:Array,default:()=>[]}},mixins:[E.c],computed:{inBuckets:function(){const t=this;let e=!1;return t.buckets.forEach((function(s){s.children.find((function(e){return e.id===t.item.id&&e.content_type.value===t.item.content_type.value}))&&(e=!0)})),e},customClasses:function(){return{...this.bucketClasses,draggable:this.draggable}},dropDownBuckets:function(){const t=[],e=this;let s=1;return this.buckets.length>0&&this.buckets.forEach((function(i){e.restrictedBySource(i.id)&&t.push({value:e.slug(i.id),label:s+" "+i.name}),s++})),t}},methods:{removeFromBucket:function(){let t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.bucket;this.$emit("remove-from-bucket",this.item,t)},toggleFeatured:function(){this.$emit("toggle-featured-in-bucket",this.item,this.bucket)},selectedBuckets:function(){const t=[],e=this;return this.buckets.length>0&&this.buckets.forEach((function(s){e.inBucketById(s.id)&&t.push(e.slug(s.id))})),t.length>0?t:[]},slug:function(t){return"bucket-"+this.bucket+"_item-"+this.item.id+"_type-"+this.item.content_type.value+"_inb-"+t},updateBucket:function(t){const e="inb-",s=this,i=s.selectedBuckets();if(s.restricted){const i=t.split(e)[1];s.inBucketById(i)||(s.$refs.bucketDropdown.toggle(),s.addToBucket(i))}else i.forEach((function(i){if(-1===t.indexOf(i)){const t=i.split(e)[1];s.$refs.bucketDropdown.toggle(),s.removeFromBucket(t)}})),Array.isArray(t)&&t.forEach((function(t){const i=t.split(e)[1];s.inBucketById(i)||(s.$refs.bucketDropdown.toggle(),s.addToBucket(i))}))}}},C=y,S=s(2528),w=(0,S.c)(C,v,T,!1,null,"2d7b9ae8",null),x=w.exports,A=function(){var t=this,e=t._self._c;return e("tr",{staticClass:"buckets__item",class:t.bucketClasses},[t.item.thumbnail?e("td",{staticClass:"buckets__itemThumbnail"},[e("img",{attrs:{src:t.item.thumbnail,alt:t.item.name}})]):t._e(),e("td",{staticClass:"buckets__itemTitle"},[e("h4",[t.item.edit?e("span",{staticClass:"f--link-underlined--o"},[e("a",{attrs:{href:t.item.edit,target:"_blank"}},[t._v(t._s(t.item.name))])]):e("span",[t._v(t._s(t.item.name))]),t.item.languages?[e("br"),e("a17-tableLanguages",{attrs:{languages:t.item.languages}})]:t._e()],2)]),t.item.publication?e("td",{staticClass:"buckets__itemDate"},[t._v(" "+t._s(t.item.publication)+" ")]):t._e(),e("td",{staticClass:"buckets__itemOptions"},[t.singleBucket&&!t.inBucketById(t.buckets[0].id)?e("a17-button",{attrs:{icon:"add"},on:{click:function(e){return t.addToBucket(t.buckets[0].id)}}},[e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"add"}})]):t.singleBucket?e("a17-button",{attrs:{icon:"add",disabled:!0}},[e("span",{directives:[{name:"svg",rawName:"v-svg"}],attrs:{symbol:"add"}})]):t._l(t.buckets,(function(s,i){return[!t.inBucketById(s.id)&&t.restrictedBySource(s.id)?e("a17-button",{key:s.id,staticClass:"bucket__action",attrs:{icon:"bucket--"+(i+1)},on:{click:function(e){return t.addToBucket(s.id)}}},[t._v(t._s(i+1))]):t.restrictedBySource(s.id)?e("a17-button",{key:s.id,staticClass:"bucket__action selected",attrs:{icon:"bucket--"+(i+1),disabled:!0}},[t._v(t._s(i+1))]):t._e()]}))],2)])},D=[],O=s(4532),I={mixins:[E.c],components:{"a17-tableLanguages":O.q_}},U=I,L=(0,S.c)(U,A,D,!1,null,null,null),F=L.exports,P={name:"A17Buckets",mixins:[g.c],props:{title:{type:String,default:"Features"},emptyBuckets:{type:String,default:"No items selected."},emptySource:{type:String,default:"No items found."},overridableMax:{type:Boolean,default:!1},restricted:{type:Boolean,default:!0},extraActions:{type:Array,default:function(){return[]}}},components:{"a17-bucket-item":x,"a17-bucket-item-source":F,"a17-fieldset":m.c,"a17-paginate":b.c,"a17-filter":f.c,"a17-vselect":k.c,draggable:d()},data:function(){return{currentBucketID:"",currentItem:"",overrideItem:!1}},computed:{...(0,_.ys)({buckets:t=>t.buckets.buckets,source:t=>t.buckets.source,dataSources:t=>t.buckets.dataSources.content_types,page:t=>t.buckets.page,availableOffsets:t=>t.buckets.availableOffsets,offset:t=>t.buckets.offset,max:t=>t.buckets.maxPage}),...(0,_.gV)(["currentSource"]),singleBucket:function(){return 1===this.buckets.length},singleSource:function(){return 1===this.dataSources.length},overrideBucketText:function(){const t=this.buckets.find((t=>t.id===this.currentBucketID));let e="",s="";return t&&(e=t.name,s=t.max),'Bucket <em>"'+e+'"</em> has a strict limit of '+s+" items. Do you want to override the first item of this bucket ?"}},methods:{addToBucket:function(t,e){const s=this.buckets.findIndex((t=>t.id===e));if(!t&&-1===s)return;this.currentBucketID=e,this.currentItem=t;const i={index:s,item:t},n=this.buckets[s].children.length;n>-1&&n<this.buckets[s].max?(this.checkRestriced(t),this.$store.commit(h.m_.ADD_TO_BUCKET,i)):this.overridableMax||this.overrideItem?(this.checkRestriced(t),this.$store.commit(h.m_.ADD_TO_BUCKET,i),this.$store.commit(h.m_.DELETE_FROM_BUCKET,{index:s,itemIndex:0}),this.overrideItem=!1):this.$refs.overrideBucket.open()},deleteFromBucket:function(t,e){const s=this.buckets.findIndex((t=>t.id===e));if(-1===s)return;const i=this.buckets[s].children.findIndex((e=>e.id===t.id&&e.content_type.value===t.content_type.value));if(-1===i)return;const n={index:s,itemIndex:i};this.$store.commit(h.m_.DELETE_FROM_BUCKET,n)},toggleFeaturedInBucket:function(t,e){const s=this.buckets.findIndex((t=>t.id===e));if(-1===s)return;const i=this.buckets[s].children.findIndex((e=>e.id===t.id&&e.content_type.value===t.content_type.value));if(-1===i)return;const n={index:s,itemIndex:i};this.$store.commit(h.m_.TOGGLE_FEATURED_IN_BUCKET,n)},checkRestriced:function(t){this.restricted&&this.buckets.forEach((e=>{e.children.forEach((s=>{s.id===t.id&&s.content_type.value===t.content_type.value&&this.deleteFromBucket(t,e.id)}))}))},sortBucket:function(t,e){const s={bucketIndex:e,oldIndex:t.moved.oldIndex,newIndex:t.moved.newIndex};this.$store.commit(h.m_.REORDER_BUCKET_LIST,s)},changeDataSource:function(t){this.$store.commit(h.m_.UPDATE_BUCKETS_DATASOURCE,t),this.$store.commit(h.m_.UPDATE_BUCKETS_DATA_PAGE,1),this.$store.dispatch(p.cp.GET_BUCKETS)},filterBucketsData:function(t){this.$store.commit(h.m_.UPDATE_BUCKETS_DATA_PAGE,1),this.$store.commit(h.m_.UPDATE_BUCKETS_FILTER,t||{search:""}),this.$store.dispatch(p.cp.GET_BUCKETS)},updateOffset:function(t){this.$store.commit(h.m_.UPDATE_BUCKETS_DATA_PAGE,1),this.$store.commit(h.m_.UPDATE_BUCKETS_DATA_OFFSET,t),this.$store.dispatch(p.cp.GET_BUCKETS)},updatePage:function(t){this.$store.commit(h.m_.UPDATE_BUCKETS_DATA_PAGE,t),this.$store.dispatch(p.cp.GET_BUCKETS)},override:function(){this.overrideItem=!0,this.addToBucket(this.currentItem,this.currentBucketID),this.$refs.overrideBucket.close()},save:function(){this.$store.dispatch(p.cp.SAVE_BUCKETS)}}},K=P,$=(0,S.c)(K,o,u,!1,null,"555b6de0",null),R=$.exports,G=s(3948),M=s(5572),N=s(9899),j=s.n(N),W=s(9812),q=s(9480);const V="BUCKETS";var X={get:function(t,e,s){j().get((0,q.sv)(),{params:t}).then((t=>{e&&"function"===typeof e&&e(t.data)})).catch((t=>{const e={message:"Get Buckets error",value:t};(0,W.q)(V,e),s&&"function"===typeof s&&s(t)}))},save(t,e,s,i){j().post(t,e).then((t=>{s&&"function"===typeof s&&s(t)})).catch((t=>{const e={message:"Buckets save error.",value:t};(0,W.q)(V,e),i&&"function"===typeof i&&i(t)}))}};const z={saveUrl:window["TWILL"].STORE.buckets.saveUrl||"",dataSources:window["TWILL"].STORE.buckets.dataSources||{},source:window["TWILL"].STORE.buckets.source||{},buckets:window["TWILL"].STORE.buckets.items||[],filter:window["TWILL"].STORE.buckets.filter||{},page:window["TWILL"].STORE.buckets.page||1,maxPage:window["TWILL"].STORE.buckets.maxPage||10,offset:window["TWILL"].STORE.buckets.offset||10,availableOffsets:window["TWILL"].STORE.buckets.availableOffsets||[10,20,30]},H={currentSource:t=>t.source.content_type},J={[h.m_.ADD_TO_BUCKET](t,e){t.buckets[e.index].children.push(e.item)},[h.m_.DELETE_FROM_BUCKET](t,e){t.buckets[e.index].children.splice(e.itemIndex,1)},[h.m_.TOGGLE_FEATURED_IN_BUCKET](t,e){const s=t.buckets[e.index].children.splice(e.itemIndex,1);s[0].starred=!s[0].starred,t.buckets[e.index].children.splice(e.itemIndex,0,s[0])},[h.m_.UPDATE_BUCKETS_DATASOURCE](t,e){t.dataSources.selected.value!==e.value&&(t.dataSources.selected=e)},[h.m_.UPDATE_BUCKETS_DATA](t,e){t.source=Object.assign({},t.source,e)},[h.m_.UPDATE_BUCKETS_FILTER](t,e){t.filter=Object.assign({},t.filter,e)},[h.m_.REORDER_BUCKET_LIST](t,e){const s=t.buckets[e.bucketIndex].children.splice(e.oldIndex,1);t.buckets[e.bucketIndex].children.splice(e.newIndex,0,s[0])},[h.m_.UPDATE_BUCKETS_DATA_OFFSET](t,e){t.offset=e},[h.m_.UPDATE_BUCKETS_DATA_PAGE](t,e){t.page=e},[h.m_.UPDATE_BUCKETS_MAX_PAGE](t,e){t.maxPage=e}},Y={[p.cp.GET_BUCKETS](t){let{commit:e,state:s}=t;X.get({content_type:s.dataSources.selected.value,page:s.page,offset:s.offset,filter:JSON.stringify(s.filter)},(t=>{e(h.m_.UPDATE_BUCKETS_DATA,t.source),e(h.m_.UPDATE_BUCKETS_MAX_PAGE,t.maxPage)}))},[p.cp.SAVE_BUCKETS](t){let{commit:e,state:s}=t;const i={};s.buckets.forEach((t=>{const e=[];t.children.forEach((t=>{e.push({id:t.id,type:t.content_type.value,starred:t.starred})})),i[t.id]=e})),X.save(s.saveUrl,{buckets:i},(t=>{e(h.gp.SET_NOTIF,{message:"Features saved. All good!",variant:"success"})}),(t=>{e(h.gp.SET_NOTIF,{message:"Your submission could not be validated, please fix and retry",variant:"error"})}))}};var Q={state:z,getters:H,mutations:J,actions:Y},Z=s(6188),tt=s(6660);i["default"].use(G.c),i["default"].use(M.c),n.c.registerModule("buckets",Q),n.c.registerModule("language",Z.c),n.c.registerModule("form",tt.c),(0,c.c)(),window["TWILL"].vm=window.vm=new i["default"]({store:n.c,el:"#app",components:{"a17-buckets":R},created:function(){(0,r.c)()}}),document.addEventListener("DOMContentLoaded",a.c)}},e={};function s(i){var n=e[i];if(void 0!==n)return n.exports;var a=e[i]={id:i,loaded:!1,exports:{}};return t[i].call(a.exports,a,a.exports,s),a.loaded=!0,a.exports}s.m=t,function(){var t=[];s.O=function(e,i,n,a){if(!i){var c=1/0;for(l=0;l<t.length;l++){i=t[l][0],n=t[l][1],a=t[l][2];for(var r=!0,o=0;o<i.length;o++)(!1&a||c>=a)&&Object.keys(s.O).every((function(t){return s.O[t](i[o])}))?i.splice(o--,1):(r=!1,a<c&&(c=a));if(r){t.splice(l--,1);var u=n();void 0!==u&&(e=u)}}return e}a=a||0;for(var l=t.length;l>0&&t[l-1][2]>a;l--)t[l]=t[l-1];t[l]=[i,n,a]}}(),function(){s.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return s.d(e,{a:e}),e}}(),function(){s.d=function(t,e){for(var i in e)s.o(e,i)&&!s.o(t,i)&&Object.defineProperty(t,i,{enumerable:!0,get:e[i]})}}(),function(){s.g=function(){if("object"===typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"===typeof window)return window}}()}(),function(){s.hmd=function(t){return t=Object.create(t),t.children||(t.children=[]),Object.defineProperty(t,"exports",{enumerable:!0,set:function(){throw new Error("ES Modules may not assign module.exports or exports.*, Use ESM export syntax, instead: "+t.id)}}),t}}(),function(){s.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)}}(),function(){s.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})}}(),function(){s.nmd=function(t){return t.paths=[],t.children||(t.children=[]),t}}(),function(){s.j=636}(),function(){var t={636:0};s.O.j=function(e){return 0===t[e]};var e=function(e,i){var n,a,c=i[0],r=i[1],o=i[2],u=0;if(c.some((function(e){return 0!==t[e]}))){for(n in r)s.o(r,n)&&(s.m[n]=r[n]);if(o)var l=o(s)}for(e&&e(i);u<c.length;u++)a=c[u],s.o(t,a)&&t[a]&&t[a][0](),t[a]=0;return s.O(l)},i=self["webpackChunk_area17_twill"]=self["webpackChunk_area17_twill"]||[];i.forEach(e.bind(null,0)),i.push=e.bind(null,i.push.bind(i))}();var i=s.O(void 0,[999,640],(function(){return s(4028)}));i=s.O(i)})();