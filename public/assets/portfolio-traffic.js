(() => {
  'use strict';

  const endpoint = 'https://atm.tiny-bits.org/api/portfolio-traffic';
  const productionHosts = new Set(['tiny-bits.org', 'www.tiny-bits.org', 'wolfgangsiegert.github.io']);

  if (!productionHosts.has(window.location.hostname.toLowerCase())) return;
  if (navigator.globalPrivacyControl === true || navigator.webdriver === true) return;

  const normalizedPath = window.location.pathname
    .replace(/\/index\.html$/, '/')
    .replace(/\/{2,}/g, '/');

  let page = null;
  if (normalizedPath === '/') {
    page = '/';
  } else if (/^\/portfolio\/?$/.test(normalizedPath)) {
    page = '/portfolio/';
  } else if (/^\/portfolio\/en\/?$/.test(normalizedPath)) {
    page = '/portfolio/en/';
  } else if (/^\/portfolio-vue\/?$/.test(normalizedPath)) {
    page = new URLSearchParams(window.location.search).get('lang') === 'en'
      ? '/portfolio-vue/en/'
      : '/portfolio-vue/';
  }

  if (page === null) return;

  let recorded = false;
  const record = () => {
    if (recorded) return;
    recorded = true;

    const body = new URLSearchParams({ version: '1', site: 'portfolio', path: page });
    void fetch(endpoint, {
      method: 'POST',
      mode: 'no-cors',
      credentials: 'omit',
      cache: 'no-store',
      keepalive: true,
      referrerPolicy: 'origin',
      body,
    }).catch(() => undefined);
  };

  if (document.prerendering) {
    document.addEventListener('prerenderingchange', record, { once: true });
  } else {
    record();
  }

  window.addEventListener('pageshow', (event) => {
    if (!event.persisted) return;
    recorded = false;
    record();
  });
})();
