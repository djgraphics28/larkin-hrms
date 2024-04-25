<div>
    <form class="form-horizontal" wire:submit.prevent="update">
        <div class="form-group row">
            <label for="first_name" class="col-sm-2 col-form-label">First name</label>
            <div class="col-sm-10">
                <input wire:model="first_name" type="text"
                    class="form-control @error ('first_name') is-invalid @enderror" id="first_name"
                    placeholder="First name">
                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="middle_name" class="col-sm-2 col-form-label">Middle name</label>
            <div class="col-sm-10">
                <input wire:model="middle_name" type="text" class="form-control" id="middle_name"
                    placeholder="Middle name">
            </div>
        </div>
        <div class="form-group row">
            <label for="last_name" class="col-sm-2 col-form-label">Last name</label>
            <div class="col-sm-10">
                <input wire:model="last_name" type="text"
                    class="form-control @error ('last_name') is-invalid @enderror" id="last_name"
                    placeholder="Last name">
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="ext_name" class="col-sm-2 col-form-label">Extension name</label>
            <div class="col-sm-10">
                <input wire:model="ext_name" type="text" class="form-control" id="ext_name"
                    placeholder="Extension name">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input wire:model="email" type="email"
                    class="form-control @error ('last_name') is-invalid @enderror" id="email"
                    placeholder="Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">Mobile Number</label>
            <div class="col-sm-10">
                <input wire:model="phone" type="text"
                    class="form-control @error ('phone') is-invalid @enderror" id="phone"
                    placeholder="Mobile Number">
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="gender" class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-10">
                <select wire:model="gender" id="gender"
                    class="form-control @error ('gender') is-invalid @enderror">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                @error('gender')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="marital_status" class="col-sm-2 col-form-label">Marital Status</label>
            <div class="col-sm-10">
                <select wire:model="marital_status" id="marital_status"
                    class="form-control @error ('marital_status') is-invalid @enderror">
                    <option value="">Select Marital Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>
                @error('marital_status')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="birth_date" class="col-sm-2 col-form-label">Birth Date</label>
            <div class="col-sm-10">
                <input wire:model="birth_date" type="date"
                    class="form-control @error ('birth_date') is-invalid @enderror" id="birth_date">
                @error('birth_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label">Address</label>
            <div class="col-sm-10">
                <input wire:model="address" type="text"
                    class="form-control @error ('address') is-invalid @enderror" id="address">
                @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-primary float-right">Update</button>
            </div>
        </div>
    </form>
</div>
