import axios from "axios";
import authHeader from "./auth-header";

const API_URL = "http://127.0.0.1/api/";

class UserService {
	getUserBoard() {
		return axios.get(API_URL + "auth/user", { headers: authHeader() });
	}
}

export default new UserService();
