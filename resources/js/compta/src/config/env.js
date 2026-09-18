/**
 * Env module Vue compta — format:
 * APP_URL / APP_URL_LOCAL / IS_PROD
 */
const isProdFlag = import.meta.env.IS_PROD
export const IS_PROD = isProdFlag === true || isProdFlag === 'true'

export const APP_URL = import.meta.env.APP_URL || ''
export const APP_URL_LOCAL = import.meta.env.APP_URL_LOCAL || ''

export const baseUrl = IS_PROD ? APP_URL : APP_URL_LOCAL

/** Prefixe API same-origin (proxy Laravel) */
export const API_PREFIX = '/api/compta'
