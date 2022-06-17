import axios from "axios";

const defaultBaseURL = process.env.MIX_API_BASE_URL;


const instance = axios.create({
    baseURL: defaultBaseURL,
    timeout: 10000,
    headers: {
        Accept: "application/json",
        "Content-Type": 'application/json'
    }
});

export {instance};
