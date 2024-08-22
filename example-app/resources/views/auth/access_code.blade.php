<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Enter Access Code') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('access_code.verify') }}">
                        @csrf

                        <div class="form-group">
                            <label for="code">{{ __('Access Code') }}</label>
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required autofocus>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Verify Code') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>