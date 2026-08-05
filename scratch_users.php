<?php echo implode(' | ', App\Models\User::all()->map(fn($u) => $u->id.':' .$u->name.':' .$u->role)->toArray());
