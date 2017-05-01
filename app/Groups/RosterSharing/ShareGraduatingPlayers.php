<?php

namespace App\Groups\RosterSharing;

use App\Group;
use App\Presentation\Describer;
use App\Program;
use App\Season;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShareGraduatingPlayers extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var Group */
    protected $group;

    /** @var Group */
    protected $program;

    /** @var Season */
    protected $season;

    public function __construct(Group $group, Program $program, Season $season)
    {
        $this->group = $group;
        $this->season = $season;
        $this->program = $program;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $players = $this->group->players()->wherePivot('grade', $this->program->max_grade)->with('guardian', 'guardian.primaryAddress')->get();
        $grade = Describer::suffix($this->program->max_grade);

        $filename = str_slug($this->group->name).'_'.Describer::suffix($this->program->max_grade).'-graders_'.$this->season->name;
        $this->subject('Graduating '.$grade.' Graders')
            ->attachData($this->playerAttachment($filename, $players)->string('csv'), $filename, [
                'mime' => 'text/csv'
            ])
            ->withGrade($grade)
            ->withGroup($this->group)
            ->withSeason($this->season)
            ->withPlayers($players);

        if ($players->count() == 0) {
            $this->markdown('emails.graduating-players.no-graduating-players');
        } else {
            $this->markdown('emails.graduating-players.graduating-players-export');
        }

        return $this;
    }

    private function playerAttachment(string $filename, Collection $players) : LaravelExcelWriter
    {
        $excel = app(Excel::class);
        return $excel->create($filename, function (LaravelExcelWriter $excel) use ($players) {
            $excel->sheet('Players', function (LaravelExcelWorksheet $sheet) use ($players) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Grade',
                    'Gender',
                    'Age',
                    'Parent/Guardian',
                    'Email',
                    'Address',
                    'Phone',
                    'Seasons Played',
                ]);

                /** @var Player $player */
                foreach ($players as $player) {
                    $sheet->appendRow([
                        $player->first_name,
                        $player->last_name,
                        $player->pivot->grade,
                        $player->gender,
                        $player->age(),
                        $player->guardian->full_name,
                        $player->guardian->email,
                        $player->guardian->primaryAddress,
                        $player->guardian->phone,
                        $player->seasons()->count(),
                    ]);
                }
            });
        });
    }
}