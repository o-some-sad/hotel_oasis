<?php
    namespace App\Console\Commands;
    use Illuminate\Console\Command;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    class CreateAdmin extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'create:admin {--name=} {--email=} {--password=} {--mobile=} {--gender=} {--national_id=} {--country=}';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Creates a new admin user with name, email , password , mobile , gender , national_id , country';

        /**
         * Execute the console command.
         */
        public function handle()
        {

            $data = [
                'name' => $this->option('name'),
                'email' => $this->option('email'),
                'password' => $this->option('password'),
                'mobile' => $this->option('mobile'),
                'gender' => $this->option('gender'),
                'national_id' => $this->option('national_id'),
                'country' => $this->option('country'),
            ];

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'mobile' => 'required|string|unique:users,mobile|min:12',
                'gender' => 'required|string|in:female,male',
                'national_id' => 'required|string||unique:users|min:14',
                'country' => 'required|string|min:3',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }
                return;
            }
            if (User::where('email',$data['email'])->exists()) {
                $this->error('A user with this email already exists.');
                return;
            }
            $hashedPassword = Hash::make($data['password']);

            if ($data['gender'] === 'male') {
                $avatar_img= 'https://media.istockphoto.com/id/1142192548/vector/man-avatar-profile-male-face-silhouette-or-icon-isolated-on-white-background-vector.jpg?s=612x612&w=0&k=20&c=DUKuRxK9OINHXt3_4m-GxraeoDDlhNuCbA9hp6FotFE=';
            } elseif ($data['gender'] === 'female') {
                $avatar_img = 'https://www.shutterstock.com/image-vector/business-woman-icon-avatar-symbol-600nw-790518412.jpg';
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' =>$hashedPassword,
                'mobile'=>$data['mobile'],
                'gender' => $data['gender'],
                'national_id'=>$data['national_id'],
                'avatar_img' => $avatar_img,
                'role' => 'admin',
                'country' => $data['country'],
            ]);
            $this->info("Admin user '{$user->name}' created successfully with ID {$user->id}.");
        }
    }
