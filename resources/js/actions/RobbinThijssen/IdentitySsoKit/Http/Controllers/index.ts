import RedirectToIdentityController from './RedirectToIdentityController';
import LogoutController from './LogoutController';

const Controllers = {
    RedirectToIdentityController: Object.assign(
        RedirectToIdentityController,
        RedirectToIdentityController,
    ),
    LogoutController: Object.assign(LogoutController, LogoutController),
};

export default Controllers;
