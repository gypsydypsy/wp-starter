require('dotenv').config();

const BASE_URL = process.env.LHCI_BASE_URL || '';
const AUTH_USER = process.env.LHCI_AUTH_USER || '';
const AUTH_PASS = process.env.LHCI_AUTH_PASS || '';
const AUTH_HEADER = AUTH_USER && AUTH_PASS
    ? 'Basic ' + Buffer.from(`${AUTH_USER}:${AUTH_PASS}`).toString('base64')
    : undefined;

console.log('BASE_URL:', BASE_URL);
console.log('AUTH_USER:', AUTH_USER);
console.log('AUTH_PASS:', AUTH_PASS);

module.exports = {
    ci: {
        collect: {
            url: [`${BASE_URL}/?lhci-test=true`],
            numberOfRuns: 1,
            settings: {
                chromeFlags: '--no-sandbox --disable-setuid-sandbox --disable-dev-shm-usage --disable-features=PrivacySandboxAdsApis',
                extraHeaders: AUTH_HEADER ? { Authorization: AUTH_HEADER } : {}
            }
        },
        assert: {
            assertions: {
                'categories.accessibility': ['error', { minScore: 0.5 }],
            },
        },
        upload: {
            target: 'temporary-public-storage',
        },
    },
};
