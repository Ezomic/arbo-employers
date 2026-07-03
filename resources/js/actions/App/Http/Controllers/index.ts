import Auth from './Auth'
import EmployerController from './EmployerController'
import EmployeeController from './EmployeeController'
import EmployeeImportController from './EmployeeImportController'
import AbsenceController from './AbsenceController'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    EmployerController: Object.assign(EmployerController, EmployerController),
    EmployeeController: Object.assign(EmployeeController, EmployeeController),
    EmployeeImportController: Object.assign(EmployeeImportController, EmployeeImportController),
    AbsenceController: Object.assign(AbsenceController, AbsenceController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers