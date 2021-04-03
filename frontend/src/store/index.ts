import Vue from "vue";
import Vuex from "vuex";

import Auth from "./modules/auth.module";
import Todo from "./modules/todo.module";

Vue.use(Vuex);

export default new Vuex.Store({
	modules: {
		Auth,
		Todo,
	},
});
