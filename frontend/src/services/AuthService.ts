import axios from "axios";

const API_URL = "http://127.0.0.1/api/auth/";

class AuthService {
	async login(email: string, password: string) {
		const response = await axios.post(API_URL + "login", {
			email,
			password,
		});
		if (response.data && response.data.user.accessToken) {
			localStorage.setItem("user", JSON.stringify(response.data.user));
		}
		return response.data.user;
	}

	logout() {
		localStorage.removeItem("user");
	}

	register(username: string, email: string, password: string) {
		return axios.post(API_URL + "register", {
			username,
			email,
			password,
		});
	}
}

export default new AuthService();
