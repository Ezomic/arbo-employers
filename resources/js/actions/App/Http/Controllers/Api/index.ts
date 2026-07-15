import CaseApiController from './CaseApiController';
import ContactPersonApiController from './ContactPersonApiController';

const Api = {
    CaseApiController: Object.assign(CaseApiController, CaseApiController),
    ContactPersonApiController: Object.assign(
        ContactPersonApiController,
        ContactPersonApiController,
    ),
};

export default Api;
