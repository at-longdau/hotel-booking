<?php

namespace Tests\Browser\Pages\Frontend\Users;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Model\Room;
use App\Model\Reservation;
use App\Model\Hotel;
use App\Model\Place;
use App\Model\Guest;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class UserUpdateBookingTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test route page show history booking of user.
     *
     * @return void
     */
    public function testUserUpdateBooking()
    {   
        $this->makeData(5);
        $this->browse(function (Browser $browser) {
            $user = User::find(2);
            $browser->logout()
                    ->loginAs($user)
                    ->visit('/profile/'.$user->id)
                    ->click('.table-user-information tbody tr:nth-child(5) td:nth-child(2) a')
                    ->assertVisible('#table-reservation');
            if ($user->reservations->count() != 0) {
                $reservationId = $browser->text('#table-reservation tbody tr:nth-child(1) td:nth-child(1)');
                $browser->click('#table-reservation tbody tr:nth-child(1) td:nth-child(6) .fa-edit')
                    ->assertSee('Show History Booking')
                    ->assertPathIs('/profile/'.$user->id.'/reservation/'.$reservationId.'/show');
            } else {
                $browser->assertMissing('#table-reservation tbody');
            }
        });
    }

    /**
     * Test cancel a booking room.
     *
     * @return void
     */
    public function testCancelBookingSuccess()
    {   
        $this->makeData(5);
        $this->browse(function (Browser $browser) {
            $user = User::find(2);
            $browser->logout()
                    ->loginAs($user)
                    ->visit('/profile/'.$user->id)
                    ->click('.table-user-information tbody tr:nth-child(5) td:nth-child(2) a')
                    ->assertVisible('#table-reservation');
            if ($user->reservations->count() != 0) {
                $reservationId = $browser->text('#table-reservation tbody tr:nth-child(1) td:nth-child(1)');
                $browser->click('#table-reservation tbody tr:nth-child(1) td:nth-child(6) .fa-edit')
                    ->assertSee('Show History Booking')
                    ->assertPathIs('/profile/'.$user->id.'/reservation/'.$reservationId.'/show');
                if ($browser->assertVisible('.btn-danger')) {
                    $browser->press('CANCEL THIS')
                            ->waitForText('Confirm deletion!')
                            ->press('OK')
                            ->assertSee('This booking room was canceled!')
                            ->assertPathIs('/profile/'.$user->id.'/reservation/'.$reservationId.'/show');
                    $this->assertTrue(Reservation::find($reservationId)->status == Reservation::STATUS_CANCELED);
                }
            } else {
                $browser->assertMissing('#table-reservation tbody');
            }
        });
    }

    /**
     * Test if user route page show a booking room of another user.
     *
     * @return void
     */
    public function testMiddleware()
    {   
        $this->makeData(5);
        $this->browse(function (Browser $browser) {
            $user = User::find(2);
            $browser->logout()
                    ->loginAs($user)
                    ->visit('/profile/'.$user->id)
                    ->click('.table-user-information tbody tr:nth-child(5) td:nth-child(2) a')
                    ->assertVisible('#table-reservation');
            $browser->visit('/profile/'.$user->id.'/reservation/4/show');   
            $reservationIds = $user->reservations()->pluck('id')->toarray();
            if (!in_array(4, $reservationIds)) {
                $browser->assertSee('403 - Forbidden')
                        ->assertPathIs('/profile/'.$user->id.'/reservation/4/show');
            } else {
                $browser->assertSee('Show History Booking')
                        ->assertPathIs('/profile/'.$user->id.'/reservation/4/show');
            }
        });
    }

    /**
     * Test 404 Page Not found when update booking.
     *
     * @return void
     */
    public function testError404()
    {   
        $this->makeData(5);
        $this->browse(function (Browser $browser) {
            $user = User::find(2);
            $browser->logout()
                    ->loginAs($user)
                    ->visit('/profile/'.$user->id)
                    ->click('.table-user-information tbody tr:nth-child(5) td:nth-child(2) a')
                    ->assertVisible('#table-reservation');
            if ($user->reservations->count() != 0) {
                $reservationId = $browser->text('#table-reservation tbody tr:nth-child(1) td:nth-child(1)');
                $browser->click('#table-reservation tbody tr:nth-child(1) td:nth-child(6) .fa-edit')
                    ->assertSee('Show History Booking')
                    ->assertPathIs('/profile/'.$user->id.'/reservation/'.$reservationId.'/show');
                if ($browser->assertVisible('.btn-danger')) {
                    $browser->press('CANCEL THIS')
                            ->waitForText('Confirm deletion!');
                    Reservation::find($reservationId)->delete();
                    $browser->press('OK')
                            ->assertSee('404 - Page Not found')
                            ->assertPathIs('/profile/'.$user->id.'/reservation/'.$reservationId);
                }
            } else {
                $browser->assertMissing('#table-reservation tbody');
            }
        });
    }

    /**
     * Make data for test.  
     *
     * @return void
     */
    public function makeData($row)
    {   
        factory(Place::class, $row)->create();
        factory(User::class, 1)->create([
            'username' => 'user1',
            'password' => bcrypt('user1'),
            'is_active' => 1,
            'is_admin' => 0,
            'full_name' => 'User1'
            ]);
        $placeIds = Place::all('id')->pluck('id')->toArray();
        $faker = Faker::create();
        for ($i = 0; $i < $row; $i++) {
            factory(Hotel::class, 1)->create([
                'place_id' => $faker->randomElement($placeIds)
            ]);
        }
        $hotelIds = Hotel::all('id')->pluck('id')->toArray();
        $faker = Faker::create();
        for ($i = 0; $i < $row; $i++) {
            factory(Room::class, 1)->create([
                'hotel_id' => $faker->randomElement($hotelIds),
            ]);
        }
        $roomIds = Room::all('id')->pluck('id')->toArray();
        $faker = Faker::create();
        for ($i = 0; $i < $row; $i++) {
            factory(Reservation::class, 1)->create([
                'room_id' => $faker->randomElement($roomIds),
                'target' => 'user',
                'target_id' => 2,
                'status' => 0
            ]);
        }
    }
}
