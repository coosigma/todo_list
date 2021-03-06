import http from "../http-common";
import { Todo } from "@/models/Todo";

class TodoDataService {
	getAll() {
		return http.get("todos", {
			transformResponse: (data) => {
				const res = JSON.parse(data);
				return res.todos;
			},
		});
	}
	get(id: string) {
		return http.get(`todos/${id}`);
	}
	create(data: { description: string; status: number }) {
		return http.post("todos", data);
	}
	update({ id, ...attr }: Todo) {
		return http.put(`todos/${id}`, attr);
	}
	delete(id: number) {
		return http.delete(`todos/${id}`);
	}
}

export default new TodoDataService();
