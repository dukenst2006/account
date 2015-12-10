<div class="grid simple">
    <div class="grid-title no-border">
        <validator name="teamSetValidation">
            <input id='teamSetName' v-if="isEditingName" type="text" v-model="teamSet.name" class="semi-bold p-t-10 p-b-10 m-l-15 edit-header" v-on:keyup.enter="saveTeamSetName()" v-on:keyup.esc="doneEditing()" v-validate:name.required.maxlength="teamSetRules" :isEditingName="true"/>
            <h3 v-else class="semi-bold p-t-10 p-b-10 m-l-15" v-on:click="editing()" :isEditingName="false">{{ teamSet.name }}</h3>
            <div class="text-small">
                <span class="text-danger" v-if="$teamSetValidation.teamSet.name.required">A name is required.</span>
                <span class="text-danger" v-if="$teamSetValidation.teamSet.name.maxlength">The name you provided is too long.</span>
            </div>
            <div class="b-grey b-b m-t-10"></div>
        </validator>
    </div>
    <div class="grid-body no-border p-t-20">
    </div>
</div>