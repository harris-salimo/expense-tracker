import axios from '@/lib/axios';
import type { Auth } from '@workspace/ui/types';
import { useMutation } from '@tanstack/vue-query';
import { computed, ref } from 'vue';

export const useAuth = () => {
    const authValue = localStorage.getItem('auth');
    const _value = ref<Partial<Auth>>(authValue ? JSON.parse(authValue) : {});
    const storedValue = computed(() => _value.value);

    const setValue = (value: Partial<Auth>) => {
        _value.value = value;
        localStorage.setItem('auth', JSON.stringify(value));
    };

    const registerMutation = useMutation({
        mutationFn: registerMutater,
    });
    const loginMutation = useMutation({
        mutationFn: loginMutater,
    });
    const logoutMutation = useMutation({
        mutationFn: logoutMutater,
    });

    return {
        auth: storedValue,
        setAuth: setValue,
        register: registerMutation.mutate,
        login: loginMutation.mutate,
        logout: logoutMutation.mutate,
        isLoading: registerMutation.isPending || loginMutation.isPending || logoutMutation.isPending,
        isSuccess: registerMutation.isSuccess || loginMutation.isSuccess || logoutMutation.isSuccess,
        isError: registerMutation.isError || loginMutation.isError || logoutMutation.isError,
        data: registerMutation.data || loginMutation.data || logoutMutation.data,
        error: registerMutation.error || loginMutation.error || loginMutation.error,
    };
};

async function registerMutater(payload: { email: string; password: string }) {
    const response = await axios.post('/register', payload);
    return response.data;
}

async function loginMutater(payload: { email: string; password: string }) {
    const response = await axios.post('/login', payload);
    return response.data;
}

async function logoutMutater(payload: {}) {
    const authValue = localStorage.getItem('auth');

    if (!authValue) {
        return;
    }

    const response = await axios.post('/logout', {}, { headers: { Authorization: `Bearer ${JSON.parse(authValue).token}` } });
    return response.data;
}
