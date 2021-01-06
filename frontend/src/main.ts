import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css";
import VeeValidate from "vee-validate";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
	faHome,
	faUser,
	faUserPlus,
	faSignInAlt,
	faSignOutAlt,
	faListAlt,
	faTrashAlt,
} from "@fortawesome/free-solid-svg-icons";

library.add(
	faHome,
	faUser,
	faUserPlus,
	faSignInAlt,
	faSignOutAlt,
	faListAlt,
	faTrashAlt
);

Vue.config.productionTip = false;

Vue.use(VeeValidate);
Vue.component("font-awesome-icon", FontAwesomeIcon);

router.beforeEach((to, from, next) => {
	const type = to.meta.type;
	if (type === "login") {
		if (window.localStorage.getItem("user")) {
			next();
		} else {
			next("/login");
		}
	} else {
		next();
	}
});

new Vue({
	router,
	store,
	render: (h) => h(App),
}).$mount("#app");
