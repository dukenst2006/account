<div v-for="quizmaster in quizmasters" class="row b-grey" :class="{ 'p-t-20': $index > 0, 'b-b': $index+1 < quizmasters.length, 'p-b-20': $index+1 < quizmasters.length }">
    <div class="col-md-6">
        <input type="text" v-model="quizmaster.first_name" class="form-control" placeholder="First Name">
    </div>
    <div class="col-md-6">
        <input type="text" v-model="quizmaster.last_name" class="form-control" placeholder="Last Name">
    </div>
    <div class="col-md-6 p-t-15">
        <input type="email" v-model="quizmaster.email" class="form-control" placeholder="Email Address">
    </div>
    <div class="col-md-4 text-center p-t-15">
        <select v-model="quizmaster.gender" class="form-control">
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
    </div>
    <div class="col-md-2 p-t-15">
        <button type="button" class="btn btn-white" @click="removeQuizmaster(quizmaster)"><i class="fa fa-minus"></i></button>
    </div>
</div>
<div class="col-md-12 text-center p-t-20">
    <button type="button" class="btn btn-white btn-small" @click="addQuizmaster()"><i class="fa fa-plus"></i> Add Quizmaster</button>
</div>