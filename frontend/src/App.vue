<template>
	<div id="app">
		<nav class="navbar navbar-expand navbar-dark bg-dark">
			<a href class="navbar-brand" @click.prevent>BL</a>
			<div v-if="currentUser" class="navbar-nav mr-auto">
				<li class="nav-item">
					<router-link to="/todos" class="nav-link">
						<font-awesome-icon icon="list-alt" /> TodoList
					</router-link>
				</li>
			</div>

			<div v-if="!currentUser" class="navbar-nav ml-auto">
				<li class="nav-item">
					<router-link to="/register" class="nav-link">
						<font-awesome-icon icon="user-plus" /> Sign Up
					</router-link>
				</li>
				<li class="nav-item">
					<router-link to="/login" class="nav-link">
						<font-awesome-icon icon="sign-in-alt" /> Login
					</router-link>
				</li>
			</div>

			<div v-if="currentUser" class="navbar-nav ml-auto">
				<li class="nav-item">
					<router-link to="/profile" class="nav-link">
						<font-awesome-icon icon="user" />
						{{ currentUser.username }}
					</router-link>
				</li>
				<li class="nav-item">
					<a class="nav-link" href @click.prevent="logOut">
						<font-awesome-icon icon="sign-out-alt" /> LogOut
					</a>
				</li>
			</div>
		</nav>

		<div class="container">
			<router-view />
		</div>
	</div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import { namespace } from "vuex-class";
const Auth = namespace("Auth");

@Component
export default class App extends Vue {
	[x: string]: any;
	@Auth.State("user")
	private currentUser!: any;

	@Auth.Action
	private signOut!: () => void;

	logOut() {
		this.signOut();
		this.$router.push("/login");
	}
}
</script>
