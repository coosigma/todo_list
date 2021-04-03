import { VuexModule, Module, Mutation, Action } from "vuex-module-decorators";
import { Todo as ITodo, Status } from "@/models/Todo";
import TodoDataService from "@/services/TodoDataService";

@Module({ namespaced: true })
class Todo extends VuexModule {
	public todos: ITodo[] = [];

	@Mutation
	public syncTodos(todos: ITodo[]): void {
		this.todos = todos;
	}

	@Mutation
	public pushTodo(todo: ITodo) {
		this.todos.push(todo);
	}

	@Mutation
	public mutateTodo(index: number, todo: ITodo) {
		this.todos[index] = todo;
	}

	@Mutation
	public removeTodo(index: number) {
		this.todos.splice(index, 1);
	}

	@Action({ rawError: true })
	public async retrieveTodos(): Promise<any> {
		try {
			const retrievedTodos = await TodoDataService.getAll();
			this.context.commit("syncTodos", retrievedTodos.data);
		} catch (err) {
			console.log(err);
		}
	}

	@Action({ rawError: true })
	public async insertTodo(data: { description: string; status: Status }) {
		try {
			const response = await TodoDataService.create(data);
			this.context.commit("pushTodo", response.data.todo);
		} catch (error) {
			console.log(error);
		}
	}

	@Action({ rawError: true })
	public async updateTodo({
		index,
		todo,
	}: {
		index: number;
		todo: ITodo;
	}): Promise<any> {
		if (todo.id === null) {
			return;
		}
		try {
			const response = await TodoDataService.update(todo);
			this.context.commit("mutateTodo", index, response.data.todo);
		} catch (error) {
			console.log(error);
		}
	}

	@Action({ rawError: true })
	public async deleteTodo(index: number, { id }: ITodo): Promise<any> {
		console.log(index);
		console.log(id);
		if (id === null) {
			return;
		}
		try {
			await TodoDataService.delete(id);
			this.context.commit("removeTodo", index);
		} catch (error) {
			console.log(error);
		}
	}

	get todo() {
		return (id: number) => this.todos.find((todo) => todo.id === id);
	}
}

export default Todo;
