import { createRouter, createWebHistory, RouterView } from "vue-router"
import AppLayout from "./layouts/AppLayout.vue"
import AuthLayout from "./layouts/AuthLayout.vue"
import { useAuth } from "./composables/useAuth";

const DashboardView = () => import("./pages/Dashboard.vue")
const ExpenseView = () => import('./pages/Expenses.vue');
const CategoryView = () => import('./pages/Categories.vue');
const RoleView = () => import('./pages/Roles.vue');
const AppearanceView = () => import('./pages/settings/Appearance.vue')
const PasswordView = () => import('./pages/settings/Password.vue');
const ProfileView = () => import('./pages/settings/Profile.vue');
const ConfirmPasswordView = () => import('./pages/auth/ConfirmPassword.vue')
const ForgotPasswordView = () => import('./pages/auth/ForgotPassword.vue');
const LoginView = () => import('./pages/auth/Login.vue');
const RegisterView = () => import('./pages/auth/Register.vue');
const ResetPasswordView = () => import('./pages/auth/ResetPassword.vue');
const VerifyEmailView = () => import('./pages/auth/VerifyEmail.vue');

export const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '',
                    redirect: '/dashboard'
                },
                {
                    path: 'dashboard',
                    name: 'dashboard-view',
                    component: DashboardView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'expenses',
                    name: 'expense-view',
                    component: ExpenseView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'categories',
                    name: 'category-view',
                    component: CategoryView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'roles',
                    name: 'role-view',
                    component: RoleView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'settings',
                    component: RouterView,
                    meta: {
                        requireAuth: true,
                    },
                    children: [
                        {
                            path: 'appearance',
                            name: 'appearance-view',
                            component: AppearanceView,
                        },
                        {
                            path: 'password',
                            name: 'password-view',
                            component: PasswordView,
                        },
                        {
                            path: 'profile',
                            name: 'profile-view',
                            component: ProfileView,
                        },
                    ],
                },
            ],
        },
        {
            path: '/auth',
            component: AuthLayout,
            children: [
                {
                    path: 'login',
                    name: 'login-view',
                    component: LoginView,
                },
                {
                    path: 'register',
                    name: 'register-view',
                    component: RegisterView,
                },
                {
                    path: 'forgot-password',
                    name: 'forgot-password-view',
                    component: ForgotPasswordView,
                },
                {
                    path: 'reset-password/:token',
                    name: 'reset-password-view',
                    component: ResetPasswordView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'verify-email',
                    name: 'verify-email-view',
                    component: VerifyEmailView,
                    meta: {
                        requireAuth: true,
                    },
                },
                {
                    path: 'confirm-password',
                    name: 'confirm-password-view',
                    component: ConfirmPasswordView,
                    meta: {
                        requireAuth: true,
                    },
                },
            ],
        },
    ],
});

  router.beforeEach((to, from, next) => {
    const { auth } = useAuth()
    const isAuthenticated = !!auth.value.token && !!auth.value.user

    if (!isAuthenticated && to.meta.requireAuth) {
      next({ name: 'login-view' })
    } else {
      next()
    }
  })

