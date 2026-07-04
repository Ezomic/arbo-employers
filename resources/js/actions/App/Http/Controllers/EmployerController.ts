import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/employer',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployerController::show
* @see app/Http/Controllers/EmployerController.php:18
* @route '/employer'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const EmployerController = { show }

export default EmployerController