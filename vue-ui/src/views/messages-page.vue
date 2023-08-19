<template>
  <div>
    <div class="header">
      <h2 class="content-block">Messages</h2>
      <dx-button style="margin-right:10px" type="success" @click="showCreate" text="Create New Message"/>
    </div>

    <dx-data-grid class="dx-card wide-card" :data-source="customStore" :focused-row-index="0" :show-borders="false"
      :focused-row-enabled="true" :column-auto-width="true" :column-hiding-enabled="true" @row-click="showDetails">
      <dx-filter-row :visible="true" />
      <dx-column data-field="uuid" caption="UUID" :width="200" :hiding-priority="2" />
      <dx-column data-field="message" caption="Message" :hiding-priority="3" />
      <dx-column data-field="createdAt" caption="Created At" data-type="datetime" :width="270" :hiding-priority="1"/>
    </dx-data-grid>
    <dx-popup class="max-width-600" :visible="createVisible" :title="createTitle">
      <dx-text-area value-change-event="keyup" @value-changed="updateMessage" :min-height="200" :max-height="600" :auto-resize-enabled="true" label="Message" />
      <div class="bottom-right-absolute">
        <dx-button type="danger" style="margin-right:10px" text="Close" @click="hideCreate"></dx-button>
        <dx-button type="success" text="Submit" @click="submitCreate"></dx-button>
      </div>
    </dx-popup>
    <dx-popup class="max-width-600" :visible="detailsVisible" :title="detailsTitle">
      <dx-form :formData="selectedRow" :readOnly="true" :labelMode="outside">
        <dxi-item dataField="uuid" label="UUID"></dxi-item>
        <dxi-item dataField="message" label="Message"></dxi-item>
        <dxi-item dataField="createdAt" label="Created At" ></dxi-item>
      </dx-form>
      <dx-button type="danger" class="bottom-right-absolute" text="Close" @click="hideDetails"></dx-button>
    </dx-popup>
  </div>
</template>
  
<script>
import { ref } from "vue";
import "devextreme/data/odata/store";
import CustomStore from 'devextreme/data/custom_store';
import DxDataGrid, {
  DxColumn,
  DxFilterRow, 
} from "devextreme-vue/data-grid";
import DxPopup from 'devextreme-vue/popup';
import DxForm from 'devextreme-vue/form';
import DxTextArea from 'devextreme-vue/text-area';
import DxButton from 'devextreme-vue/button';

const serviceUrl = 'http://localhost:8080/api/messages';
const customStore = ref(new CustomStore({
  key: "uuid",
  load: (loadOptions) => {
    const params = {
      sort_by: loadOptions.sort ? loadOptions.sort[0].selector : "uuid", 
      order_by: loadOptions.sort && loadOptions.sort[0].desc ? "desc" : "asc",
    };

    return fetch(`${serviceUrl}?${new URLSearchParams(params)}`)
      .then(response => response.json())
      .then(data => {
        return {
          data: Object.values(data),
        };
      });
  },
  fetchById: (uuid) => {
    return fetch(`${serviceUrl}/${uuid}`)
      .then(response => response.json())
      .then(data => {
        return data;
      });
  },
  insert: (message) => {
    return fetch(`${serviceUrl}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: message
    })
    .then(response => response.text())
    .then(async data => {
      const newMessage = await fetch(`${serviceUrl}/${data}`)
        .then(response => response.json())
        .then(data => {
          return data;
        });
        window.location.reload()
      return {
        data: [newMessage]
      };
    });
  }
}));

export default {
  setup() {
    return {
      customStore,
      detailsVisible: ref(false),
      detailsTitle: "Message Details",
      createTitle: "Create New Message",
      createVisible: ref(false),
      selectedRow: ref(null),
      newMessage: ref(''),
    };
  },
  components: {
    DxDataGrid,
    DxColumn,
    DxFilterRow,
    DxPopup,
    DxButton,
    DxForm,
    DxTextArea,
  },
  methods: {
    showDetails(event) {
      this.selectedRow = event.data;
      this.detailsVisible = true;
    },
    hideDetails() {
      this.detailsVisible = false;
    },
    showCreate() {
      this.createVisible = true;
    },
    submitCreate() {
      this.createVisible = false;
      this.customStore.insert(this.newMessage);
    },
    hideCreate() {
      this.createVisible = false;
    },
    updateMessage(event) {
      this.newMessage = event.value
    }
  }
};
</script>

<style>
.max-width-600 {
  max-width: 600px;
}
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.bottom-right-absolute {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
}
</style>