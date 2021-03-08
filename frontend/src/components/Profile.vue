<template>
	<div class="container">
		<header class="jumbotro">
			<h3>
				<strong>{{ currentUser.name }}</strong> Profile
			</h3>
		</header>
		<p>
			<strong>Token:</strong>
			{{ currentUser.accessToken.substring(0, 20) }} ...
			{{ currentUser.accessToken.substr(currentUser.accessToken.length - 20) }}
		</p>
		<p>
			<strong>Id:</strong>
			{{ currentUser.id }}
		</p>
		<p>
			<strong>Email:</strong>
			{{ currentUser.email }}
		</p>
	</div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import { namespace } from "vuex-class";
const Auth = namespace("Auth");

@Component
export default class Profile extends Vue {
	[x: string]: any;
	@Auth.State("user")
	private currentUser!: any;

	mounted() {
		if (!this.currentUser) {
			this.$router.push("/login");
		}
	}
}
</script>
