import http from "../http-common";

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
	create(data: any) {
		return http.post("todos", data);
	}
	update(id: string, data: any) {
		return http.put(`todos/${id}`, data);
	}
	delete(id: string) {
		return http.delete(`todos/${id}`);
	}
}

export default new TodoDataService();
