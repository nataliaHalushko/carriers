(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2391d4bb"],{"51ac":function(t,e,i){"use strict";var s=i("a1f4"),n=i.n(s);n.a},a1f4:function(t,e,i){},b752:function(t,e,i){"use strict";i.r(e);var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("HeaderTicket",{attrs:{name:t.client.first_name,"handle-open":t.openModal}}),i("Modal",{attrs:{"is-open-modal":t.isOpenModal,"close-modal":t.closeModal}}),i("b-container",{directives:[{name:"show",rawName:"v-show",value:!t.isMobile,expression:"!isMobile"}]},[t.ticketsbuy.length?i("h2",{staticClass:"text-left  d-flex align-items-center buy-trip__title"},[t._v(" "+t._s(t.ticketsbuy[0].detail.route.from.settlement)+" - "+t._s(t.ticketsbuy[0].detail.route.to.settlement))]):t._e()]),i("b-container",{directives:[{name:"show",rawName:"v-show",value:!t.isMobile,expression:"!isMobile"}],staticClass:"mb-1"},[i("RowFind")],1),t.isMobile?i("b-container",{staticClass:"mb-2"},[t.ticketsbuy.length?i("div",{staticClass:"tablo-title text-left  d-flex align-items-center justify-content-between px-3 justify-content-between"},[i("span",[t._v(t._s(t.ticketsbuy[0].detail.route.from.settlement)+" → "+t._s(t.ticketsbuy[0].detail.route.to.settlement)+" ")]),i("span",{staticClass:"tablo-title__date"},[t._v(" "+t._s(t.ticketsbuy[0].detail.route.from.date))])]):t._e()]):i("b-container",[t.ticketsbuy.length?i("div",{staticClass:"tablo-title text-left d-flex align-items-center px-2 "},[t._v(" Квитки "+t._s(t.ticketsbuy[0].detail.route.from.settlement)+" - "+t._s(t.ticketsbuy[0].detail.route.to.settlement)+" на "),i("span",{staticClass:"tablo-title__date"},[t._v(t._s(t.ticketsbuy[0].detail.route.from.date))])]):t._e()]),i("b-container",[i("Table")],1),i("p",{staticClass:"text-center pt-4 title-center-info"},[t._v(" Щоб отримати більше інформації про перевезення та замовити квиток ")]),i("button",{staticClass:"btn-do mb-5 text-uppercase btn-become"},[t._v("Стати своїм")]),i("Footer")],1)},n=[],a=i("5530"),o=i("c40e"),c=i("3f0e"),l=i("d17b"),r=i("9d10"),u=i("6e4a"),b=i("2f62"),d={name:"BuyTrip",data:function(){return{cityFROM:"",cityTO:"",dataSettlement:"",client:{},cityArray:[],selectedCity:{city:"Вінниця",place_id:"ChIJiWRaGWVbLUcR_nTd7lnh1Ms"},isOpenModal:!1,isMobile:!1}},computed:Object(a["a"])({},Object(b["c"])({token:function(t){return t.token},isAuth:function(t){return t.isAuth},ticketsbuy:function(t){return t.ticketsbuy}})),methods:Object(a["a"])(Object(a["a"])({},Object(b["b"])(["AUTH_LOGOUT"])),{},{LOGOUT:function(){var t=this;this.$store.dispatch("AUTH_LOGOUT").then((function(){t.$router.push({path:"home"})}))},openModal:function(){this.isOpenModal=!0},closeModal:function(){this.isOpenModal=!1}}),components:{HeaderTicket:o["a"],Table:c["a"],RowFind:l["a"],Footer:r["a"],Modal:u["a"]},created:function(){var t=this;window.innerWidth>576?this.isMobile=!1:this.isMobile=!0;var e={"Content-type":"application/json",Authorization:"Bearer ".concat(this.token)};axios.get("web/user",{headers:e}).then((function(e){t.client=e.data.result})).catch((function(t){401===t.response.status&&console.log("Не авторизований")}))}},p=d,f=(i("51ac"),i("2877")),h=Object(f["a"])(p,s,n,!1,null,null,null);e["default"]=h.exports}}]);
//# sourceMappingURL=chunk-2391d4bb.9aa9fd54.js.map