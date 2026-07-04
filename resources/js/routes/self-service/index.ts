import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'

/**
* @see \App\Http\Controllers\SelfServiceController::show
* @route '/self-service'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
show.definition = { methods: ['get', 'head'], url: '/self-service' } satisfies RouteDefinition<['get', 'head']>
show.url = (options?: RouteQueryOptions) => show.definition.url + queryParams(options)
show.get = show

/**
* @see \App\Http\Controllers\SelfServiceController::gdprExport
* @route '/self-service/gdpr-export'
*/
export const gdprExport = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: gdprExport.url(options),
    method: 'get',
})
gdprExport.definition = { methods: ['get', 'head'], url: '/self-service/gdpr-export' } satisfies RouteDefinition<['get', 'head']>
gdprExport.url = (options?: RouteQueryOptions) => gdprExport.definition.url + queryParams(options)
gdprExport.get = gdprExport

const selfService = { show, gdprExport }
export default selfService
