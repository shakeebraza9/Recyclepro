class JqueryApiService {

    constructor() {
        this.baseUrl = 'https://www.recyclepro.co.uk/rp-dashboard/';
    }

    _buildUrl(endpoint) {

        const cleanEndpoint = endpoint.startsWith('/')
            ? endpoint.substring(1)
            : endpoint;

        return `${this.baseUrl}${cleanEndpoint}`;
    }

    async get(endpoint) {

        try {

            const url = this._buildUrl(endpoint);

            return await $.getJSON(url);

        } catch (error) {

            console.error(`API Get Error [${endpoint}]:`, error);

            throw error;
        }
    }

    async post(endpoint, data) {

        try {

            const url = this._buildUrl(endpoint);

            return await $.ajax({
                url: url,
                type: 'POST',
                data: data,
                dataType: 'json'
            });

        } catch (error) {

            console.error(`API Post Error [${endpoint}]:`, error);

            throw error;
        }
    }
}

window.JqueryApiService = JqueryApiService;