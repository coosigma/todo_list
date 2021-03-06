<template>
	<tr
		class="list-group-item"
		:class="{ active: activated }"
		@click="$emit('selected', $event)"
	>
		<td>
			<input
				type="checkbox"
				:id="cb_id()"
				v-model="todo.status"
				@change="onChecked"
			/>
		</td>
		<td>
			<input
				class="left-margin"
				type="text"
				:id="inp_id()"
				v-model="todo.description"
				@change="onInput"
			/>
		</td>
		<td v-if="activated">
			<button
				type="button"
				class="btn btn-danger"
				@click="$emit('deleted', todo.id)"
				style="margin-left: 75%;"
			>
				<font-awesome-icon icon="trash-alt" />
			</button>
		</td>
	</tr>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import { Todo, Status } from "@/models/Todo";
@Component
export default class TodoElement extends Vue {
	@Prop({ required: true }) todo!: Todo;
	@Prop({ required: true, default: false })
	activated!: boolean;
	cb_id() {
		return `cb${this.todo.id}`;
	}
	inp_id() {
		return `inp${this.todo.id}`;
	}
	onChecked(event: InputEvent) {
		this.$emit("updated", {
			id: this.todo.id,
			status: (<HTMLInputElement>event.target).checked
				? Status.done
				: Status.undone,
		});
	}
	onInput(event: InputEvent) {
		this.$emit("updated", {
			id: this.todo.id,
			description: (<HTMLInputElement>event.target).value,
		});
	}
}
Vue.component("todo-element", TodoElement);
</script>

<style scoped>
.left-margin {
	margin-left: 5%;
}
input[type="text"] {
	width: 20em;
	border: none;
}
tr.selected {
	background-color: #34aeeb;
}
</style>
