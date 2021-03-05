<template>
	<div class="container">
		<header class="jumbotron">
			<h3>{{ content }}</h3>
		</header>
	</div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import UserService from "@/services/UserService";

@Component
export default class Home extends Vue {
	private content = "";

	async mounted() {
		try {
			const response = await UserService.getUserBoard();
			this.content = response.data;
		} catch (error) {
			this.content =
				(error.response &&
					error.response.data &&
					error.response.data.message) ||
				error.message ||
				error.toString();
		}
	}
}
</script>
