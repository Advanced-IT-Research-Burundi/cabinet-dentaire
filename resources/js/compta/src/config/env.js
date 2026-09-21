/**
 * Env module Vue compta — format:
 * APP_URL / APP_URL_LOCAL / IS_PROD
 */
const isProdFlag = import.meta.env.IS_PROD
export const IS_PROD = isProdFlag === true || isProdFlag === 'true'

export const APP_URL = import.meta.env.APP_URL || ''
export const APP_URL_LOCAL = import.meta.env.APP_URL_LOCAL || ''

function normalizeUrl(url) {
  return String(url || '').replace(/\/+$/, '')
}

export const baseUrl = normalizeUrl(IS_PROD ? APP_URL : APP_URL_LOCAL)

if (!baseUrl) {
  throw new Error('APP_URL ou APP_URL_LOCAL doit être configuré pour le front compta.')
}

/** Prefixe API compta, construit uniquement depuis .env compta. */
export const API_PREFIX = `${baseUrl}/api`
