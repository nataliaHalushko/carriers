(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-594251e6"],{"03eb":function(t,s,i){t.exports=i.p+"img/event.3371fb0b.svg"},"1d68":function(t,s,i){t.exports=i.p+"img/arrow-right.490c6a5d.svg"},"1d7f":function(t,s,i){},"33b2":function(t,s,i){},b0c0:function(t,s,i){var a=i("83ab"),e=i("9bf2").f,n=Function.prototype,l=n.toString,c=/^\s*function ([^ (]*)/,r="name";a&&!(r in n)&&e(n,r,{configurable:!0,get:function(){try{return l.call(this).match(c)[1]}catch(t){return""}}})},b671:function(t,s,i){"use strict";var a=i("33b2"),e=i.n(a);e.a},b7fc:function(t,s,i){"use strict";var a=i("1d7f"),e=i.n(a);e.a},c40e:function(t,s,i){"use strict";var a=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("b-container",{staticClass:"mb-2",attrs:{id:"header-ticket"}},[a("b-navbar",{staticClass:"px-0 py-3",attrs:{toggleable:"lg"}},[a("a",[a("router-link",{attrs:{to:t.ticketsLink}},[a("b-navbar-brand",{staticClass:"mr-5"},[a("img",{staticClass:"logo",attrs:{src:i("e372"),alt:""}})])],1)],1),a("div",{staticClass:"header-ticket__toggler",on:{click:t.showMenu}},[a("span"),a("span"),a("span")]),a("b-collapse",{attrs:{id:"nav-collapse","is-nav":""}},[a("b-navbar-nav",[a("b-nav-item",[a("router-link",{staticStyle:{color:"#484848 !important"},attrs:{to:"/ticket-bus"}},[t._v("Купити квиток")])],1),a("b-nav-item",{attrs:{href:"#"}},[t._v("Розклад")]),a("b-nav-item",{attrs:{href:"/about"}},[t._v("Про нас")])],1),a("b-navbar-nav",{staticClass:"ml-auto"},[a("b-nav-item",{staticClass:"support-text pr-5",attrs:{href:"#"}},[t._v("Служба підтримки")]),t.isAuth?a("div",{staticClass:"btn-list"},[a("button",{staticClass:"btn-open mx-auto position-relative",on:{click:t.isBtn}},[a("img",{staticClass:"pl-1 pr-2",attrs:{src:i("8e08"),alt:""}}),t._v(" "+t._s(t.name)+" "),a("img",{class:{arrow:t.isArrow},attrs:{src:i("6e93"),alt:""}})]),t.list?a("div",{staticClass:"list-auth"},[a("ul",{staticClass:"text-left"},[a("li",{staticClass:"item-list-li pt-4"},[a("router-link",{attrs:{to:"/ticket-bus"}},[t._v("Купити квиток")])],1),a("li",{staticClass:"item-list-li"},[a("a",{attrs:{href:"#"}},[t._v("Розклад рейсів")])]),a("li",{staticClass:"item-list-li"},[a("router-link",{attrs:{to:"/profile"}},[t._v("Профіль")])],1),a("li",{staticClass:"item-list-li"},[a("router-link",{attrs:{to:"/tickets"}},[t._v("Мої квитки")])],1),a("li",{staticClass:"item-list-li li-log-out",on:{click:t.LOGOUT}},[t._v("Вийти")])])]):t._e()]):a("button",{staticClass:"btn-open mx-auto",on:{click:t.login}},[a("img",{staticClass:"pl-1 pr-2",attrs:{src:i("8f3e"),alt:""}}),t._v(" Вхід для своїх ")]),a("b-nav-item-dropdown",{staticClass:"ml-5 localization-list support-text",attrs:{text:t.activeLang,right:""}},[a("b-dropdown-item",{attrs:{disabled:"УКР"==t.activeLang},on:{click:t.langChange}},[t._v("УКР")]),a("b-dropdown-item",{attrs:{disabled:"EN"==t.activeLang},on:{click:t.langChange}},[t._v("EN")])],1)],1)],1),a("div",{staticClass:"navbar-mobile",class:t.menuIsVisible?"visible":""},[a("div",{staticClass:"navbar-mobile__top d-flex justify-content-between "},[a("router-link",{attrs:{to:t.ticketsLink}},[a("b-navbar-brand",{staticClass:"mr-5"},[a("img",{staticClass:"logo",attrs:{src:i("e372"),alt:""}})])],1),a("div",{staticClass:"close-menu",on:{click:t.showMenu}},[a("span"),a("span")])],1),t.isAuth?a("div",{staticClass:"d-flex justify-content-between align-itens-end mb-4"},[a("div",{staticClass:"d-flex justify-content-start"},[a("div",[a("img",{staticClass:"mobile-profile__avatar",attrs:{src:i("8e08"),alt:"profile"}})]),a("div",{staticClass:"d-flex flex-column align-item-start text-left justify-content-between"},[a("span",{staticClass:"mobile-profile__text"},[t._v(t._s(t.name))]),a("span",{staticClass:"mobile-profile__text"},[a("router-link",{attrs:{to:"/profile"}},[t._v("Профіль")])],1)])]),a("div",{staticClass:"d-flex align-items-end"},[a("a",{staticClass:"mobile-profile__logout",attrs:{href:"#"},on:{click:t.LOGOUT}},[a("img",{attrs:{src:i("c5d0"),alt:"logout"}}),t._v("Вийти")])])]):a("div",{staticClass:"navbar-mobile__profile d-flex "},[a("button",{staticClass:"btn-open mx-auto",on:{click:t.login}},[a("img",{staticClass:"pl-1 pr-2 ",attrs:{src:i("8f3e"),alt:""}}),t._v(" Вхід для своїх ")])]),a("div",{staticClass:"navbar-mobile__links d-flex flex-column align-items-start "},[a("router-link",{staticClass:"mb-5",attrs:{to:"/ticket-bus"}},[t._v("Купити квиток")]),a("a",{staticClass:"mt-3",attrs:{href:"#"}},[t._v("Пасажирам")]),a("a",{attrs:{href:"#"}},[t._v("Перевізникам")]),a("a",{staticClass:"support-text pr-5",attrs:{href:"#"}},[t._v("Служба підтримки")])],1),a("b-nav-item-dropdown",{staticClass:"support-text navbar-mobile__lang",attrs:{text:t.activeLang,right:""}},[a("b-dropdown-item",{attrs:{disabled:"УКР"==t.activeLang},on:{click:t.langChange}},[t._v("УКР")]),a("b-dropdown-item",{attrs:{disabled:"EN"==t.activeLang},on:{click:t.langChange}},[t._v("EN")])],1),a("div",{staticClass:"d-flex flex-column align-items-start navbar-mobile__contacts"},[a("p",{staticClass:"font-weight-bold footer-link-title my-2"},[t._v("Контакти")]),a("ul",{staticClass:"d-flex d-flex flex-column align-items-start"},[a("li",{staticClass:"mb-3"},[a("a",{attrs:{href:"tel:380670000000"}},[t._v("+38 067 000-00-00")])]),a("li",{staticClass:"mb-3"},[a("a",{attrs:{href:"mailto:burburbus.ua@gmail.com"}},[t._v("burburbus.ua@gmail.com")])]),a("li",[a("ul",{staticClass:"d-flex justify-content-between ml-0 footer-socials"},[a("li",[a("a",{attrs:{href:"#"}},[a("img",{attrs:{src:i("e07a"),alt:""}})])]),a("li",[a("a",{attrs:{href:"#"}},[a("img",{attrs:{src:i("de74"),alt:""}})])]),a("li",[a("a",{attrs:{href:"#"}},[a("img",{attrs:{src:i("f6e3"),alt:""}})])]),a("li",[a("a",{attrs:{href:"#"}},[a("img",{attrs:{src:i("85fb"),alt:""}})])])])])])])],1)],1),t.menuIsVisible?a("div",{staticClass:"overlay",on:{click:t.showMenu}}):t._e()],1)},e=[],n=(i("b0c0"),i("5530")),l=i("2f62"),c={name:"HeaderTicket",data:function(){return{list:!1,isArrow:!1,isBtnActive:!1,isLogo:!1,link:"/",activeLang:"УКР",menuIsVisible:!1}},props:{handleOpen:{type:Function,required:!1},name:{type:String}},methods:Object(n["a"])(Object(n["a"])({},Object(l["b"])(["AUTH_LOGOUT"])),{},{LOGOUT:function(){var t=this;this.$store.dispatch("AUTH_LOGOUT").then((function(){t.$router.push("./")}))},isBtn:function(){this.list=!this.list,this.isArrow=!this.isArrow,this.isLogo=!this.isLogo},langChange:function(t){this.activeLang=t.target.innerText},login:function(){this.handleOpen()},showMenu:function(){this.menuIsVisible=!this.menuIsVisible}}),computed:Object(n["a"])(Object(n["a"])({},Object(l["c"])({token:function(t){return t.token},isAuth:function(t){return t.isAuth}})),{},{ticketsLink:function(){return"Tickets"===this.$route.name?"/ticket-bus":"/"}})},r=c,o=(i("b671"),i("2877")),v=Object(o["a"])(r,a,e,!1,null,null,null);s["a"]=v.exports},d464:function(t,s,i){"use strict";i.r(s);var a=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",[a("HeaderTicket",{attrs:{name:t.client.first_name,"handle-open":t.openModal}}),a("Modal",{attrs:{"is-open-modal":t.isOpenModal,"close-modal":t.closeModal}}),t._m(0),t.loading?t._e():a("div",{staticClass:"container-wrapper"},[a("div",{staticClass:"d-flex flex-start"},[a("button",{class:{green:t.showActive},attrs:{id:"btn-active-ticket"},on:{click:function(s){t.showActive=!0}}},[t._v("Активні ")]),a("button",{class:{green:!t.showActive},attrs:{id:"btn-archive"},on:{click:function(s){t.showActive=!1}}},[t._v("Архів")])]),a("transition",{attrs:{name:"component-fade",mode:"out-in"}},[t.showActive?a("div",{key:"show",staticClass:"d-flex flex-wrap"},[t._l(t.ticketsActive,(function(s,e){return a("div",{key:e,staticClass:"box d-flex flex-column justify-content-between pl-2 pr-2"},[a("div",{staticClass:"d-flex justify-content-between align-items-center"},[a("span",{staticClass:"item-city"},[t._v(t._s(s.from))]),a("span",[a("img",{attrs:{src:i("1d68"),alt:""}})]),a("span",{staticClass:"item-city"},[t._v(t._s(s.to))])]),a("div",{staticClass:"d-flex justify-content-between align-items-center pb-2 ticket-departure__time-wrapper"},[a("div",{staticClass:"text-left"},[a("div",{staticClass:"item-position"},[t._v("Час відп.")]),a("div",{staticClass:"item-bold"},[t._v(t._s(s.time))]),a("div",{staticClass:"green-line"})]),a("div",[a("img",{attrs:{src:i("5c20")}})]),a("div",{staticClass:"text-center pl-2 pr-2"},[a("div",{staticClass:"item-position"},[t._v("Місце")]),a("div",{staticClass:"item-bold "},[t._v(t._s(s.seat))]),a("div",{staticClass:"fiolet-line mx-auto"})])]),a("div",[a("span",{staticClass:"text-center name-trip"},[t._v(t._s(s.trip))]),a("div",[a("img",{staticStyle:{width:"72%"},attrs:{src:s.qr,alt:""}})])]),a("div",{staticClass:"d-flex justify-content-between align-items-center pl-2 pr-2"},[a("img",{attrs:{src:i("03eb"),alt:""}}),a("div",{staticClass:"trip-date"},[t._v(t._s(s.notification))]),a("img",{attrs:{src:i("e320"),alt:"i"}})]),a("div",{staticClass:"show-more-link mb-4"},[a("a",{staticClass:"link-details",attrs:{href:s.google_maps}},[t._v("Детальніше")])])])})),t.ticketsActive?t._e():a("p",{staticClass:"pt-5 pb-5"},[t._v("Нажаль у Вас немає активних квитків")])],2):a("div",{key:"hide",staticClass:"d-flex  flex-wrap"},[t._l(t.ticketsArchive,(function(s,e){return a("div",{key:e,staticClass:"box box-archive d-flex flex-column justify-content-between pl-2 pr-2"},[a("div",{staticClass:"d-flex justify-content-between align-items-center"},[a("span",{staticClass:"item-city"},[t._v(t._s(s.from))]),a("span",[a("img",{attrs:{src:i("1d68"),alt:""}})]),a("span",{staticClass:"item-city"},[t._v(t._s(s.to))])]),a("div",{staticClass:"d-flex justify-content-between align-items-center pb-2 ticket-departure__time-wrapper"},[a("div",{staticClass:"text-left"},[a("div",{staticClass:"item-position"},[t._v("Час відп.")]),a("div",{staticClass:"item-bold"},[t._v(t._s(s.time))]),a("div",{staticClass:"green-line"})]),a("div",[a("img",{attrs:{src:i("5c20")}})]),a("div",{staticClass:"text-center pl-2 pr-2"},[a("div",{staticClass:"item-position"},[t._v("Місце")]),a("div",{staticClass:"item-bold "},[t._v(t._s(s.seat))]),a("div",{staticClass:"fiolet-line mx-auto"})])]),a("div",[a("span",{staticClass:"text-center name-trip"},[t._v(t._s(s.trip))]),a("div",[a("img",{attrs:{src:i("e629"),alt:""}})])]),a("div",{staticClass:"d-flex justify-content-between align-items-center pl-2 pr-2"},[a("img",{attrs:{src:i("03eb"),alt:""}}),a("div",{staticClass:"trip-date"},[t._v(t._s(s.notification))]),a("img",{attrs:{src:i("e320"),alt:"i"}})]),a("div",{staticClass:"show-more-link mb-4"},[a("a",{staticClass:"link-details",attrs:{href:s.google_maps}},[t._v("Детальніше")])])])})),t.ticketsArchive?t._e():a("p",{staticClass:"pt-5 pb-5"},[t._v("Нажаль у Вас немає архівних квитків")]),a("div",{staticClass:"filter-text"},[t._v("Фільтр")])],2)])],1),a("Footer")],1)},e=[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"container-wrapper"},[i("div",{staticClass:"text-left "},[i("a",{staticClass:"link-ticket",attrs:{href:"#"}},[t._v("Головна")]),t._v(" / "),i("a",{staticStyle:{color:"#499625"},attrs:{href:"#"}},[t._v("Квитки")])]),i("div",{staticClass:"text-left pt-3 title-bur pb-3"},[t._v("Мої квитки")])])}],n=(i("b0c0"),i("5530")),l=i("2f62"),c=i("9d10"),r=i("c40e"),o=i("6e4a"),v={name:"Tickets",data:function(){return{loading:!1,client:{},showActive:!0,loginType:"",isActive:!0,isArhive:!1,ticketsActive:[],ticketsArchive:[],isOpenModal:!1}},computed:Object(n["a"])(Object(n["a"])({},Object(l["c"])(["token","isAuth"])),{},{ticketsLink:function(){return"tickets"===this.$route.name?"/ticketsbus":"/"}}),created:function(){var t=this;this.loading=!0;var s={"Content-type":"application/json",Authorization:"Bearer ".concat(this.token)};axios.get("web/user",{headers:s}).then((function(s){t.client=s.data.result})),axios.get("web/ticket/my",{headers:s}).then((function(s){console.log(s),t.ticketsActive=s.data.result.active,t.ticketsArchive=s.data.result.archive})),this.loading=!1},components:{Footer:c["a"],HeaderTicket:r["a"],Modal:o["a"]},methods:{openModal:function(){this.isOpenModal=!0},closeModal:function(){this.isOpenModal=!1}}},d=v,u=(i("b7fc"),i("2877")),m=Object(u["a"])(d,a,e,!1,null,null,null);s["default"]=m.exports},e320:function(t,s,i){t.exports=i.p+"img/export-links.e084cb80.svg"},e629:function(t,s,i){t.exports=i.p+"img/qa-ticket-code.78d90606.svg"}}]);
//# sourceMappingURL=chunk-594251e6.7a0d6279.js.map