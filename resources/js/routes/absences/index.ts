import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:14
* @route '/employer/absences'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employer/absences',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:14
* @route '/employer/absences'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:14
* @route '/employer/absences'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:14
* @route '/employer/absences'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:14
* @route '/employer/absences'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\AbsenceController::mutate
* @see app/Http/Controllers/AbsenceController.php:43
* @route '/employer/absences/{case}/mutate'
*/
export const mutate = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: mutate.url(args, options),
    method: 'post',
})

mutate.definition = {
    methods: ["post"],
    url: '/employer/absences/{case}/mutate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AbsenceController::mutate
* @see app/Http/Controllers/AbsenceController.php:43
* @route '/employer/absences/{case}/mutate'
*/
mutate.url = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { case: args }
    }

    if (Array.isArray(args)) {
        args = {
            case: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        case: args.case,
    }

    return mutate.definition.url
            .replace('{case}', parsedArgs.case.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AbsenceController::mutate
* @see app/Http/Controllers/AbsenceController.php:43
* @route '/employer/absences/{case}/mutate'
*/
mutate.post = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: mutate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::mutate
* @see app/Http/Controllers/AbsenceController.php:43
* @route '/employer/absences/{case}/mutate'
*/
const mutateForm = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: mutate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::mutate
* @see app/Http/Controllers/AbsenceController.php:43
* @route '/employer/absences/{case}/mutate'
*/
mutateForm.post = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: mutate.url(args, options),
    method: 'post',
})

mutate.form = mutateForm

/**
* @see \App\Http\Controllers\AbsenceController::close
* @see app/Http/Controllers/AbsenceController.php:61
* @route '/employer/absences/{case}/close'
*/
export const close = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(args, options),
    method: 'post',
})

close.definition = {
    methods: ["post"],
    url: '/employer/absences/{case}/close',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AbsenceController::close
* @see app/Http/Controllers/AbsenceController.php:61
* @route '/employer/absences/{case}/close'
*/
close.url = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { case: args }
    }

    if (Array.isArray(args)) {
        args = {
            case: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        case: args.case,
    }

    return close.definition.url
            .replace('{case}', parsedArgs.case.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AbsenceController::close
* @see app/Http/Controllers/AbsenceController.php:61
* @route '/employer/absences/{case}/close'
*/
close.post = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::close
* @see app/Http/Controllers/AbsenceController.php:61
* @route '/employer/absences/{case}/close'
*/
const closeForm = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: close.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::close
* @see app/Http/Controllers/AbsenceController.php:61
* @route '/employer/absences/{case}/close'
*/
closeForm.post = (args: { case: string | number } | [caseParam: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: close.url(args, options),
    method: 'post',
})

close.form = closeForm

const absences = {
    store: Object.assign(store, store),
    mutate: Object.assign(mutate, mutate),
    close: Object.assign(close, close),
}

export default absences