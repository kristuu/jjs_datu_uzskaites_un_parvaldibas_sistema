import{_ as ie}from"./layout-dashboard.vue_vue_type_script_setup_true_lang-7654bfd2.js";import{u as E,a as F,_ as G,r as ce,b as de,c as ue}from"./use-language-code-conversion-7d4be340.js";import{_ as pe}from"./icon-language-179135f3.js";import{_ as K}from"./_plugin-vue_export-helper-c27b6911.js";import{o,c as a,a as e,d as I,l as _,A as W,s as q,h as t,t as p,v as h,u as n,n as O,F as R,x as _e,e as z,T as he,q as N,w as u,i as me,g as ge,Z as fe,k as ve}from"./app-6e494c7d.js";import{_ as xe}from"./base-button.vue_vue_type_script_setup_true_lang-6faaa598.js";import{_ as ye,a as be}from"./icon-key-4e7fad60.js";import{_ as we}from"./icon-trash-837dcd50.js";import{_ as Ce}from"./icon-pencil-0371ebcb.js";import{_ as Le}from"./icon-clipboard-2369bfa0.js";import{_ as A}from"./flag.vue_vue_type_script_setup_true_lang-0fbe3bde.js";import{_ as $e}from"./icon-arrow-right-da4bfcdd.js";import{r as ke}from"./XCircleIcon-6ce17f6e.js";import"./icon-publish-73e612df.js";import"./logo-487e8493.js";import"./use-auth-937202b1.js";import"./transition-c671a28f.js";const ze={},Te={viewBox:"0 0 15 15",xmlns:"http://www.w3.org/2000/svg",fill:"currentColor"},He=e("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M14 2.25H7.16L6.5 0H2C1.175 0 0.5 0.675 0.5 1.5V11.25C0.5 12.075 1.175 12.75 2 12.75H7.25L8 15H14C14.825 15 15.5 14.325 15.5 13.5V3.75C15.5 2.925 14.825 2.25 14 2.25ZM4.3775 9.4425C2.69 9.4425 1.31 8.07 1.31 6.375C1.31 4.68 2.6825 3.3075 4.3775 3.3075C5.1575 3.3075 5.87 3.585 6.4325 4.11L6.485 4.155L5.5625 5.04L5.5175 5.0025C5.3 4.8 4.9325 4.56 4.3775 4.56C3.395 4.56 2.5925 5.3775 2.5925 6.375C2.5925 7.3725 3.395 8.19 4.3775 8.19C5.405 8.19 5.8475 7.5375 5.9675 7.095H4.31V5.9325H7.2725L7.28 5.985C7.31 6.1425 7.3175 6.285 7.3175 6.4425C7.3175 8.205 6.11 9.4425 4.3775 9.4425ZM9.7925 9.435C9.455 9.045 9.1475 8.61 8.9 8.16L9.3875 9.8325L9.7925 9.435ZM9.4775 7.59H8.735L8.5025 6.81H11.495C11.495 6.81 11.24 7.7925 10.325 8.865C9.935 8.4 9.6575 7.9425 9.4775 7.59ZM14 14.25C14.4125 14.25 14.75 13.9125 14.75 13.5V3.75C14.75 3.3375 14.4125 3 14 3H7.385L8.27 6.03H9.74V5.25H10.52V6.03H13.25V6.81H12.2975C12.0575 7.755 11.5325 8.67 10.8575 9.4425L12.89 11.4525L12.3425 12L10.3325 9.9825L9.6425 10.6725L10.25 12.75L8.75 14.25H14Z"},null,-1),Ve=[He];function Me(m,i){return o(),a("svg",Te,Ve)}const Se=K(ze,[["render",Me]]),je={class:"flex flex-wrap divide-x"},Be={class:"px-4 py-3 md:w-64"},Ie={class:"mt-2 flex items-center"},Ze=["textContent"],De={class:"flex flex-1 items-center px-4 py-3"},Pe={class:"text-gray-700"},Ue={class:"flex divide-x"},Ne=["disabled"],Ae=I({__name:"machine-translate-item",props:{language:{},phrase:{}},emits:["useTranslation"],setup(m,{emit:i}){const l=m,y=i,d=s=>{y("useTranslation",s)},{convertLanguageCode:$}=F(),b=_(1),g=_(1),T=l.phrase.value,r=$(l.language),H=r||"en-US",k=_(void 0),f=E(T,{lang:H,voice:k,pitch:g,rate:b});let w;const C=_([]);return W(()=>{f.isSupported.value&&setTimeout(()=>{w=window.speechSynthesis,C.value=w.getVoices(),k.value=C.value[0]})}),(s,c)=>{const V=Se,M=pe,v=G,x=q("tooltip");return o(),a("div",je,[e("div",Be,[e("div",Ie,[t(V,{class:"mr-2 size-4 text-gray-500"}),e("div",{class:"text-sm font-medium text-gray-700",textContent:p(s.phrase.engine)},null,8,Ze)])]),e("div",De,[e("span",Pe,p(s.phrase.value),1)]),e("div",Ue,[h((o(),a("button",{class:"flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600",onClick:c[0]||(c[0]=S=>d(s.phrase.value))},[t(M,{class:"size-6"})])),[[x,"Use this"]]),h((o(),a("button",{class:O(["flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100",{"cursor-not-allowed opacity-50":!n(r)," hover:bg-blue-100 hover:text-blue-600":n(r)}]),disabled:!n(r),onClick:c[1]||(c[1]=S=>n(r)&&n(f).speak())},[t(v,{class:"size-6"})],10,Ne)),[[x,n(r)?"Speak":"Language not supported"]])])])}}}),Ee={key:0,class:"flex w-full flex-col divide-y"},Fe=e("div",{class:"flex h-20 w-full items-center justify-center px-4 py-6"},[e("span",{class:"text-sm text-gray-500"},"More integrations coming in next releases...")],-1),Ge={key:1,class:"relative flex size-full min-h-[250px]"},Ke={class:"absolute left-0 top-0 flex min-h-full w-full flex-col items-center justify-center backdrop-blur-sm"},We=e("span",{class:"mt-4 text-gray-500"},"No suggestions found...",-1),qe=I({__name:"machine-translate",props:{language:{},suggestedTranslations:{}},emits:["useTranslation"],setup(m,{emit:i}){const l=i,y=d=>{l("useTranslation",d)};return(d,$)=>{const b=Ae;return Object.keys(d.suggestedTranslations).length>0?(o(),a("div",Ee,[(o(!0),a(R,null,_e(d.suggestedTranslations,g=>(o(),z(b,{key:g.id,phrase:g,language:d.language,onUseTranslation:y},null,8,["phrase","language"]))),128)),Fe])):(o(),a("div",Ge,[e("div",Ke,[t(n(ce),{class:"size-12 text-gray-200"}),We])]))}}}),Oe={},Re={xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true"},Je=e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"},null,-1),Qe=[Je];function Xe(m,i){return o(),a("svg",Re,Qe)}const Ye=K(Oe,[["render",Xe]]),es={class:"w-full bg-white shadow"},ss={class:"mx-auto flex w-full max-w-7xl items-center justify-between px-6 lg:px-8"},ts={class:"flex w-full items-center"},ns={class:"flex w-full items-center gap-3 py-4"},os={class:"h-5 shrink-0"},as={class:"flex items-center space-x-2"},ls=["textContent"],rs={class:"flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500"},is=["textContent"],cs={class:"mx-auto max-w-7xl px-6 py-10 lg:px-8"},ds={class:"flex w-full flex-col lg:flex-row"},us={class:"relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100"},ps={class:"flex items-center justify-between border-b"},_s={class:"flex gap-2 px-4 py-2.5"},hs={class:"h-5 shrink-0"},ms={class:"flex items-center space-x-2"},gs=["textContent"],fs=["textContent"],vs={class:"flex items-center divide-x border-l"},xs={type:"button",class:"group h-full p-3 hover:bg-red-50"},ys={dir:"auto",class:"flex min-h-[204px] w-full px-4 py-2.5"},bs={class:"flex size-full flex-wrap gap-x-1 gap-y-0.5"},ws={key:1,class:"flex h-full text-gray-600"},Cs={class:"flex w-full items-center justify-center gap-4 border-t border-blue-200 px-4 py-1.5"},Ls={class:"flex w-full select-none items-center gap-1 text-gray-400"},$s=["innerHTML"],ks=["disabled"],zs={class:"flex h-16 w-full items-center justify-center lg:h-auto lg:w-32"},Ts={class:"w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400"},Hs={class:"flex items-center justify-between border-b px-4"},Vs={class:"flex gap-2 py-2.5"},Ms={class:"h-5 shrink-0"},Ss={class:"flex items-center space-x-2"},js=["textContent"],Bs=["textContent"],Is={key:0,class:"rounded-md border border-red-400 bg-red-50 px-3 py-1"},Zs={class:"flex items-center gap-1"},Ds={class:"shrink-0"},Ps={class:"text-sm text-red-700"},Us={class:"flex"},Ns={class:"grid grid-cols-2 border-t border-blue-200"},As=e("span",{class:"flex w-auto"},"Save",-1),Es=e("span",{class:"hidden md:flex"},"Translation",-1),Fs=e("div",{class:"flex h-48 w-full items-center justify-center px-4 py-6"},[e("span",{class:"text-sm text-gray-500"},"Coming soon...")],-1),rt=I({__name:"edit",props:{phrase:{},translation:{},source:{},similarPhrases:{},suggestedTranslations:{}},setup(m){const i=m,l=he({phrase:_(i.phrase.value)||_("")}),y=()=>{l.post(route("ltu.phrases.update",{translation:i.translation.id,phrase:i.phrase.uuid}),{onSuccess:()=>{l.reset()}})},d=s=>{l.phrase=s},{convertLanguageCode:$}=F(),b=_(1),g=_(1),T=i.phrase.value,r=$(i.translation.language.code),H=r||"en-US",k=_(void 0),f=E(T,{lang:H,pitch:g,rate:b});let w;const C=_([]);return W(()=>{f.isSupported.value&&setTimeout(()=>{w=window.speechSynthesis,C.value=w.getVoices(),k.value=C.value[0]})}),(s,c)=>{const V=fe,M=A,v=ve,x=$e,S=ye,Z=A,J=Le,Q=Ce,X=we,Y=de,ee=Ye,se=G,te=be,ne=xe,oe=qe,j=N("tab"),ae=ue,le=N("tabs"),re=ie,L=q("tooltip");return o(),a(R,null,[t(V,{title:"Translate"}),t(re,null,{default:u(()=>{var D,P,U;return[e("div",es,[e("div",ss,[e("div",ts,[e("div",ns,[t(v,{href:s.route("ltu.phrases.index",s.translation.id),class:"flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100"},{default:u(()=>[e("div",os,[t(M,{"country-code":s.translation.language.code,width:"w-5"},null,8,["country-code"])]),e("div",as,[e("div",{class:"text-sm font-semibold text-gray-600",textContent:p(s.translation.language.name)},null,8,ls)])]),_:1},8,["href"]),e("div",null,[t(x,{class:"size-6 text-gray-400"})]),e("div",rs,[t(S,{class:"size-4"}),e("span",{class:"text-sm",textContent:p(s.phrase.key)},null,8,is)])])]),h((o(),z(v,{href:s.route("ltu.phrases.index",s.translation.id),class:"flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200"},{default:u(()=>[t(x,{class:"size-6 text-gray-400"})]),_:1},8,["href"])),[[L,"Go back"]])])]),e("div",cs,[e("div",ds,[e("div",us,[e("div",ps,[e("div",_s,[e("div",hs,[t(Z,{"country-code":s.source.language.code},null,8,["country-code"])]),e("div",ms,[e("div",{class:"text-sm font-semibold text-gray-800",textContent:p(s.source.language.name)},null,8,gs),e("div",{class:"rounded-md border px-1.5 py-0.5 text-xs text-gray-500",textContent:p(s.source.language.code)},null,8,fs)])]),e("div",vs,[h((o(),a("button",{type:"button",class:"group h-full p-3 hover:bg-blue-50",onClick:c[0]||(c[0]=B=>d(s.phrase.source.value))},[t(J,{class:"size-5 text-gray-400 group-hover:text-blue-600"})])),[[L,"Copy"]]),h((o(),z(v,{href:s.route("ltu.source_translation.edit",s.phrase.source.uuid),type:"button",class:"group h-full p-3 hover:bg-blue-50"},{default:u(()=>[t(Q,{class:"size-5 text-gray-400 group-hover:text-blue-600"})]),_:1},8,["href"])),[[L,"Edit Source Key"]]),h((o(),a("button",xs,[t(X,{class:"size-5 text-gray-400 group-hover:text-red-600"})])),[[L,"Delete"]])])]),e("div",ys,[e("div",bs,[(D=s.phrase.source)!=null&&D.value_html.length?(o(),z(Y,{key:0,phrase:s.phrase.source.value_html,copyable:""},null,8,["phrase"])):(o(),a("div",ws,p((P=s.phrase.source)==null?void 0:P.value),1))])]),e("div",Cs,[e("div",Ls,[t(ee,{class:"size-4"}),e("div",{class:"text-xs",innerHTML:(U=s.phrase.source)==null?void 0:U.file.nameWithExtension},null,8,$s)]),h((o(),a("button",{class:O(["flex size-6 shrink-0 items-center justify-center text-gray-400",{"cursor-not-allowed opacity-50":!n(r),"hover:text-gray-700":n(r)}]),disabled:!n(r)&&!n(f).isPlaying.value,onClick:c[1]||(c[1]=B=>n(r)&&n(f).speak())},[t(se,{class:"size-5"})],10,ks)),[[L,n(r)?"Speak":"Language not supported"]])])]),e("div",zs,[t(x,{class:"size-10 rotate-90 text-blue-200 lg:rotate-0"})]),e("div",Ts,[e("div",Hs,[e("div",Vs,[e("div",Ms,[t(Z,{"country-code":s.translation.language.code},null,8,["country-code"])]),e("div",Ss,[e("div",{class:"text-sm font-semibold text-gray-800",textContent:p(s.translation.language.name)},null,8,js),e("div",{class:"rounded-md border px-1.5 py-0.5 text-xs text-gray-500",textContent:p(s.translation.language.code)},null,8,Bs)])]),n(l).errors.phrase?(o(),a("div",Is,[e("div",Zs,[e("div",Ds,[t(n(ke),{class:"size-5 text-red-400","aria-hidden":"true"})]),e("div",Ps,p(n(l).errors.phrase),1)])])):me("",!0)]),e("div",Us,[t(te,{id:"textArea",modelValue:n(l).phrase,"onUpdate:modelValue":c[2]||(c[2]=B=>n(l).phrase=B),dir:"auto",rows:"7",autofocus:"",class:"size-full resize-none rounded-none border-none px-4 py-2.5 shadow-none ring-transparent focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0"},null,8,["modelValue"])]),e("div",Ns,[t(v,{href:s.route("ltu.phrases.index",s.translation.id),class:"flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100"},{default:u(()=>[ge(" Cancel ")]),_:1},8,["href"]),t(ne,{"is-loading":n(l).processing,variant:"primary",class:"items-center rounded-none !py-4 !text-sm !font-medium uppercase",onClick:y},{default:u(()=>[As,Es]),_:1},8,["is-loading"])])])]),t(le,null,{default:u(()=>[t(j,{name:"Suggestions",class:"w-full",prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.3417 7.60001L18.3334 8.11668L13.7917 12.0583L15.15 17.9167L10 14.8083L4.85002 17.9167L6.21669 12.0583L1.66669 8.11668L7.65835 7.60834L10 2.08334L12.3417 7.60001ZM6.86669 15.1417L10 13.25L13.1417 15.15L12.3084 11.5833L15.075 9.18334L11.425 8.86668L10 5.50001L8.58335 8.85834L4.93335 9.17501L7.70002 11.575L6.86669 15.1417Z"></path></svg>'},{default:u(()=>[t(oe,{"suggested-translations":s.suggestedTranslations,language:s.translation.language.code,onUseTranslation:d},null,8,["suggested-translations","language"])]),_:1}),t(j,{prefix:'<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.5 3.33334C8.81665 3.33334 5.83331 6.31668 5.83331 10C5.83331 13.6833 8.81665 16.6667 12.5 16.6667C16.1833 16.6667 19.1666 13.6833 19.1666 10C19.1666 6.31668 16.1833 3.33334 12.5 3.33334ZM12.5 15C9.74165 15 7.49998 12.7583 7.49998 10C7.49998 7.24168 9.74165 5.00001 12.5 5.00001C15.2583 5.00001 17.5 7.24168 17.5 10C17.5 12.7583 15.2583 15 12.5 15ZM5.83331 5.29168C3.89165 5.97501 2.49998 7.82501 2.49998 10C2.49998 12.175 3.89165 14.025 5.83331 14.7083V16.45C2.95831 15.7083 0.833313 13.1083 0.833313 10C0.833313 6.89168 2.95831 4.29168 5.83331 3.55001V5.29168Z"></path></svg>',name:"Similar"},{default:u(()=>[t(ae,{"similar-phrases":s.similarPhrases},null,8,["similar-phrases"])]),_:1}),t(j,{prefix:'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z"/></svg>',name:"Versions"},{default:u(()=>[Fs]),_:1})]),_:1})])]}),_:1})],64)}}});export{rt as default};
