export default {
    doaminAuthorizationChecker(domain) {
        return axios.get(`/api/authorizationChecker/domain/${domain}`);
    },
    menuAuthorizationChecker(menu) {
        return axios.get(`/api/authorizationChecker/menu/${menu}`);
    }
}