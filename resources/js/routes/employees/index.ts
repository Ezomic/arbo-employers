import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/employer/employees/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\EmployeeController::search
* @see app/Http/Controllers/EmployeeController.php:17
* @route '/employer/employees/search'
*/
searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

search.form = searchForm

/**
* @see \App\Http\Controllers\EmployeeController::store
* @see app/Http/Controllers/EmployeeController.php:69
* @route '/employer/employees'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employer/employees',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\EmployeeController::store
* @see app/Http/Controllers/EmployeeController.php:69
* @route '/employer/employees'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EmployeeController::store
* @see app/Http/Controllers/EmployeeController.php:69
* @route '/employer/employees'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\EmployeeController::store
* @see app/Http/Controllers/EmployeeController.php:69
* @route '/employer/employees'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\EmployeeController::store
* @see app/Http/Controllers/EmployeeController.php:69
* @route '/employer/employees'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const employees = {
    search: Object.assign(search, search),
    store: Object.assign(store, store),
}

export default employees