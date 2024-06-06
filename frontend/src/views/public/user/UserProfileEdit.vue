<template>
  <Transition mode="out-in" name="fade">
    <div v-if="fetchDataStore.show" class="container-xl mt-3 lg:mt-0">
      <div class="grid">
        <!-- Left Sidebar -->
        <div class="col-12 lg:col-3">
          <div class="grid">
            <div class="col-12 h-full">
              <div class="grid">
                <div class="col-12">
                  <div
                    class="p-2 bg-primary lg:bg-white rounded shadow text-center h-full"
                  >
                    <h1 class="m-0 text-white lg:text-primary">
                      {{ $t("user_profile").toLocaleUpperCase() }}
                    </h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-12 lg:col-9">
          <div class="p-3 bg-white rounded shadow h-full">
            <TabView>
              <TabPanel :header="$t(`tabpanel.user.info`)">
                <UserInfoTab />
              </TabPanel>
              <TabPanel :header="$t(`tabpanel.user.change_password`)">
                <ChangePasswordTab />
              </TabPanel>
              <TabPanel :header="$t(`tabpanel.user.address`)">
                <AddressTab />
              </TabPanel>
            </TabView>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { onBeforeMount, onMounted } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useAuthStore } from "@/stores/authStore";
import UserInfoTab from "@/components/user/UserInfoTab.vue";
import ChangePasswordTab from "@/components/user/ChangePasswordTab.vue";
import AddressTab from "@/components/user/AddressTab.vue";

const fetchDataStore = useFetchDataStore();
const authStore = useAuthStore();

onBeforeMount(() => {
  fetchDataStore.resetInstance();
  fetchDataStore.instance = authStore.user;
});

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>
