<template>
	<div class="list row">
		<div class="col-md-10">
			<h3>My Todo List</h3>
			<div class="input-group mb-3">
				<input
					type="text"
					class="form-control"
					id="new_description"
					name="new_description"
					v-model="newTodo.description"
					@keyup="handleKeyUp"
				/>
				<div class="input-group-append">
					<button
						class="btn btn-outline-secondary"
						type="button"
						@click="addTodo()"
					>
						Add
					</button>
				</div>
			</div>
		</div>
		<div v-if="unauthorized" class="col-md-10">
			<div class="alert alert-danger">
				<strong>Unauthorized!</strong> Your token may have expired. You can try
				to log out and log in again.
			</div>
		</div>
		<div class="col-md-10">
			<table class="list-group">
				<template v-for="(todo, index) in todos">
					<todo-element
						:todo="todo"
						:activated="index == currentIndex"
						:key="index"
						@selected.stop="setActiveTodo(todo, index, ...arguments)"
						@updated="update(index, ...arguments)"
						@deleted="deleteTodo(index, todo)"
					></todo-element>
				</template>
			</table>
		</div>
		<div class="col-md-10">
			<todo-graph :allTodos="todos"></todo-graph>
		</div>
	</div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import TodoDataService from "../services/TodoDataService";
import TodoElement from "./TodoElement.vue";
import TodoGraph from "./TodoGraph.vue";
import { Todo, Status } from "@/models/Todo";

@Component<TodoList>({
	components: { TodoElement, TodoGraph },
})
export default class TodoList extends Vue {
	public todos: Todo[] = [];
	public currentTodo: Todo | null = null;
	public currentIndex: number = -1;
	public title: string = "";
	public emptyTodo: Todo = {
		id: null,
		description: "",
		status: Status.undone,
	};
	public newTodo: Todo = Object.assign({}, this.emptyTodo);
	public unauthorized = false;

	async retrieveTodos() {
		try {
			const response = await TodoDataService.getAll();
			this.todos = response.data;
		} catch (error) {
			if (error.response.status === 401) {
				this.unauthorized = true;
			}
			console.log(error);
		}
	}

	async update(index: number, todo: Todo) {
		if (todo.id === null) {
			return;
		}
		try {
			const response = await TodoDataService.update(todo);
			this.todos[index] = response.data.todo;
			// Deep watcher cannot catch frequent updating operations
			this.retrieveTodos();
		} catch (error) {
			console.log(error);
		}
	}

	async deleteTodo(index: number, { id }: Todo) {
		if (id === null) {
			return;
		}
		try {
			const response = await TodoDataService.delete(id);
			this.todos.splice(index, 1);
		} catch (error) {
			console.log(error);
		}
	}

	handleKeyUp(event: KeyboardEvent) {
		if (event.key === "Enter") {
			this.addTodo();
		}
	}

	async addTodo() {
		const data = {
			description: this.newTodo.description,
			status: this.newTodo.status,
		};
		try {
			const response = await TodoDataService.create(data);
			this.newTodo = Object.assign({}, this.emptyTodo);
			this.todos.push(response.data.todo);
		} catch (error) {
			console.log(error);
		}
	}

	refreshList() {
		this.retrieveTodos();
		this.currentTodo = null;
		this.currentIndex = -1;
	}

	setActiveTodo(todo: Todo, index: number, event: Event) {
		this.currentTodo = todo;
		this.currentIndex = index;
	}

	inactivate() {
		this.currentTodo = null;
		this.currentIndex = -1;
	}

	mounted() {
		this.retrieveTodos();
	}

	created() {
		// "blur" event
		window.addEventListener("click", this.clickOther);
	}

	destroyed() {
		window.removeEventListener("click", this.clickOther);
	}

	clickOther() {
		this.inactivate();
	}
}
</script>

<style scoped>
.list {
	text-align: left;
	max-width: 750px;
	margin: auto;
}
</style>
