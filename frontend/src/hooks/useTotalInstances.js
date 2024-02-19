import { ref } from 'vue';

export const useTotalInstances = () => {
    const totalInstances = ref(0);
    const handleTotalInstancesUpdate = (newTotalInstances) => {
        totalInstances.value = newTotalInstances;
    };

    return { totalInstances, handleTotalInstancesUpdate };
}