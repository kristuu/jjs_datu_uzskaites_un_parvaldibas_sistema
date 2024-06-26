<template>
  <div class="col-12">
    <div class="p-2 bg-primary lg:bg-white rounded shadow w-full">
      <Divider class="mt-1" />
      <div class="text-center">
        <h3 class="m-0 text-white lg:text-primary">
          {{ $t(`authorized_as`).toLocaleUpperCase() }}
        </h3>
        <p class="m-0 text-white lg:text-primary">
          {{ `${user.name} ${user.surname}` }}
        </p>
      </div>
      <Divider class="mb-1" />
    </div>
  </div>
  <div class="col-12">
    <div class="p-2 bg-white rounded shadow w-100">
      <div class="text-center text-primary">
        <div class="flex justify-content-between mx-1">
          <i
            :class="
              unreadNotificationsCount > 0 ? 'bi bi-bell-fill' : 'bi bi-bell'
            "
          />
          <h3 class="m-0">
            {{ $t(`notifications`).toLocaleUpperCase() }}
          </h3>
          <Button
            class="p-0"
            icon="bi bi-arrow-clockwise"
            text
            @click="fetchNotifications"
          />
        </div>
        <Divider class="my-2" />
        <div v-if="isFetchingNotifications" class="px-2">
          <Skeleton class="mb-1" height="1rem" />
          <Skeleton height="1rem" width="75%" />
          <Divider class="my-2" />
          <Skeleton class="mb-1" height="1rem" />
          <Skeleton height="1rem" width="75%" />
          <Divider class="my-2" />
          <Skeleton class="mb-1" height="1rem" />
          <Skeleton height="1rem" width="75%" />
        </div>
        <div v-if="!isFetchingNotifications && notifications.length === 0">
          <span class="text-sm">Paziņojumu saraksts ir tukšs</span>
        </div>
        <div v-if="notifications.length > 0" class="text-sm text-start px-2">
          <div v-for="(notification, index) in notifications" :key="index">
            <div
              class="cursor-pointer"
              @click="
                () => {
                  chosenNotification = notification;
                  openNotificationDetails(notification);
                }
              "
            >
              <p
                :class="!notification.read ? 'fw-bold' : 'fw-normal'"
                class="m-0"
              >
                {{ notification.short_message }}
              </p>
            </div>
            <Divider v-if="index !== notifications.length - 1" class="my-2" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <Dialog
    v-model:visible="notificationDetailsVisible"
    :pt="{
      root: 'border-none',
      mask: {
        style: 'backdrop-filter: blur(4px)',
      },
    }"
    class="container-sm"
    dismissableMask
    modal
    position="top"
    style="background: none; max-width: 900px"
  >
    <template #container="{ closeCallback }">
      <div class="grid mt-2">
        <div class="col-12">
          <div class="bg-primary rounded text-center shadow">
            <h1 class="py-4 m-0">PAZIŅOJUMS</h1>
          </div>
        </div>
        <div class="col-12">
          <div class="bg-white rounded p-3">
            <div class="d-flex justify-content-between">
              <div>
                <div class="fw-bold">Paziņojuma virsraksts</div>
                <div>
                  {{ chosenNotification.short_message }}
                </div>
              </div>
              <Button
                class="fs-2 p-0"
                icon="bi bi-x-lg"
                text
                @click="closeCallback"
              />
            </div>
            <Divider />
            <div style="max-height: 50vh; overflow: hidden auto">
              <div v-html="chosenNotification.long_message" />
              <Divider />
              <div class="text-end">
                <Button
                  label="ATZĪMĒT KĀ NELASĪTU"
                  outlined
                  size="small"
                  text
                  @click="markNotificationAsUnread"
                />
                <Button
                  class="ms-2"
                  label="DZĒST"
                  size="small"
                  @click="deleteNotification"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Dialog>
</template>

<script setup>
import { useAuthStore } from "@/stores/authStore";
import { onBeforeMount, onMounted, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import axios from "@/services/axios";

const fetchDataStore = useFetchDataStore();

const authStore = useAuthStore();
const user = authStore.user;

let notifications = ref([]);
let unreadNotificationsCount = ref(0);
let chosenNotification = ref(null);

let isFetchingNotifications = ref(false);

let notificationDetailsVisible = ref(false);

const openNotificationDetails = (notification) => {
  if (!notification.read) {
    notification.read = true;
    unreadNotificationsCount.value -= 1;

    axios.patch(`/api/read_notification/${notification.id}`).catch((error) => {
      console.error("Error marking notification as read:", error);
    });
  }
  notificationDetailsVisible.value = true;
};

const markNotificationAsUnread = async () => {
  await axios
    .patch(`/api/unread_notification/${chosenNotification.value.id}`)
    .then((response) => {
      console.log(response);
      notificationDetailsVisible.value = false;
      chosenNotification.value = null;
      fetchNotifications();
    })
    .catch((error) => {
      console.log(error);
    });
};

const deleteNotification = async () => {
  await axios
    .delete(`/api/delete_notification/${chosenNotification.value.id}`)
    .then((response) => {
      console.log(response);
      notificationDetailsVisible.value = false;
      chosenNotification.value = null;
      fetchNotifications();
    })
    .catch((error) => {
      console.log(error);
    });
};

const fetchNotifications = async () => {
  notifications.value = [];
  isFetchingNotifications.value = true;
  await axios
    .get(`/api/notifications`)
    .then((response) => {
      console.log(response);
      notifications.value = response.data.notifications;
      unreadNotificationsCount.value = response.data.unread_count;
    })
    .catch((error) => {
      console.error(error);
    })
    .finally(() => {
      isFetchingNotifications.value = false;
    });
};

onBeforeMount(async () => {
  await fetchNotifications();
});

onMounted(() => {
  fetchDataStore.showComponents();
});
</script>
