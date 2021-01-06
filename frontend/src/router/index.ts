import Vue from "vue";
import VueRouter, { RouteConfig } from "vue-router";
import Login from "@/components/Login.vue";
import Register from "@/components/Register.vue";

Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
	{
		path: "/",
		alias: "/todos",
		name: "todos",
		// lazy-loaded
		component: () => import("../components/TodoList.vue"),
		meta: {
			type: "login",
		},
	},
	{
		path: "/todos",
		component: () => import("../components/TodoList.vue"),
		meta: {
			type: "login",
		},
	},
	{
		path: "/login",
		component: Login,
	},
	{
		path: "/register",
		component: Register,
	},
	{
		path: "/profile",
		name: "profile",
		component: () => import("../components/Profile.vue"),
		meta: {
			type: "login",
		},
	},
	{
		path: "/user",
		name: "user",
		component: () => import("../components/BoardUser.vue"),
		meta: {
			type: "login",
		},
	},
];

const router = new VueRouter({
	mode: "history",
	base: process.env.BASE_URL,
	routes,
});

export default router;
