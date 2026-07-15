import Api from './Api';
import Auth from './Auth';
import SelfServiceController from './SelfServiceController';
import EmployerController from './EmployerController';
import EmployeeController from './EmployeeController';
import EmployeeImportController from './EmployeeImportController';
import AbsenceController from './AbsenceController';
import UserController from './UserController';
import Settings from './Settings';

const Controllers = {
    Api: Object.assign(Api, Api),
    Auth: Object.assign(Auth, Auth),
    SelfServiceController: Object.assign(
        SelfServiceController,
        SelfServiceController,
    ),
    EmployerController: Object.assign(EmployerController, EmployerController),
    EmployeeController: Object.assign(EmployeeController, EmployeeController),
    EmployeeImportController: Object.assign(
        EmployeeImportController,
        EmployeeImportController,
    ),
    AbsenceController: Object.assign(AbsenceController, AbsenceController),
    UserController: Object.assign(UserController, UserController),
    Settings: Object.assign(Settings, Settings),
};

export default Controllers;
