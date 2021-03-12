import axios from "axios";
import authHeader from "./auth-header";

class UserService {
	getUserBoard() {
		return axios.get(process.env.VUE_APP_API_URL + "auth/user", {
			headers: authHeader(),
		});
	}
}

export default new UserService();
