import axios from "axios";
import authHeader from "./services/auth-header";

const instance = axios.create({
	baseURL: "http://127.0.0.1/api/",
	headers: {
		"Content-type": "application/json",
	},
});

// Set the AUTH token for any request
instance.interceptors.request.use(function(config) {
	config.headers.Authorization = authHeader().Authorization;
	return config;
});

export default instance;
