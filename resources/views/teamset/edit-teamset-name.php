<validator name="teamSetValidation">
    <input id='teamSetName' v-if="isEditingName" type="text" v-model="newName" class="bold m-l-15 edit-header" @keyup.enter="saveTeamSetName()" @keyup.esc="doneEditing()" @blur="doneEditing()" v-validate:name.required.maxlength="teamSetRules" :isEditingName="true"/>
    <h3 v-else class="semi-bold m-l-15" @click="editing()" :isEditingName="false">{{ teamSet.name }} <span class="fa fa-edit"></span></h3>
    <div class="text-small">
        <span class="text-danger" v-if="$teamSetValidation.teamSet.name.required">A name is required.</span>
        <span class="text-danger" v-if="$teamSetValidation.teamSet.name.maxlength">The name you provided is too long.</span>
    </div>
</validator>