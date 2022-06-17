import {instance as axios} from "../utility/axios";

/**
 * Get all urls data in a list
 * @returns {Promise<AxiosResponse<any>>}
 */
export const getUrlsList = async () => {
    return axios.get('url-shorten');
};

/**
 * Create new URL shortcode
 * @param data
 * @returns {Promise<AxiosResponse<any>>}
 */
export const createShortUrl = async data => {
    return axios.post('url-shorten', data);
};

/**
 * Get the original URL
 *
 * @param hashCode
 * @returns {Promise<AxiosResponse<any>>}
 */
export const getOriginalUrl = async hashCode => {
    return axios.get(`url-shorten/get-url/${hashCode}`);
};
