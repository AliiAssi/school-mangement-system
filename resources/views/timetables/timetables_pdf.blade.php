<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f9fafb;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937; /* Tailwind gray-800 */
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .class-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937; /* Tailwind gray-800 */
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #e5e7eb; /* Tailwind gray-200 */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f9fafb; /* Tailwind gray-50 */
            font-size: 14px;
            font-weight: 500;
            color: #6b7280; /* Tailwind gray-500 */
        }
        td {
            font-size: 14px;
            color: #4b5563; /* Tailwind gray-700 */
        }
        .day {
            font-weight: 600;
        }
        .slot {
            padding: 5px;
            background-color: #e0f2fe; /* Tailwind blue-100 */
        }
        .slot:hover {
            background-color: #b9e1ff; /* Tailwind blue-200 */
        }
        .teacher-name {
            font-weight: 600;
        }
        .slot-details {
            color: #6b7280; /* Tailwind gray-500 */
        }
        .subject-name {
            color: #d1d5db; /* Tailwind gray-300 */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            TimeTables PDF
        </div>

        @foreach ($classes as $class)
            <div class="section">
                <div class="class-title">
                    {{ $class->name }} TimeTable
                </div>
                @php
                    // Fetch timetables for the current class
                    $classTimetables = $timetables->filter(function ($item) use ($class) {
                        return $item->class_id == $class->id;
                    });
                @endphp
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            @for ($hour = 8; $hour <= 17; $hour++)
                                <th>{{ $hour }}:00</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <tr>
                                <td class="day">{{ $day }}</td>

                                @for ($hour = 8; $hour <= 17; $hour++)
                                    @php
                                        $time = sprintf('%02d:00', $hour);
                                        $slots = $classTimetables->filter(function ($item) use ($day, $time) {
                                            $slotStart = (int) explode(':', $item->start_time)[0];
                                            $slotEnd = (int) explode(':', $item->end_time)[0];
                                            $currentHour = (int) explode(':', $time)[0];
                                            return $item->day_of_week === $day &&
                                                $slotStart <= $currentHour &&
                                                $slotEnd > $currentHour;
                                        });
                                    @endphp

                                    <td class="{{ $slots->isNotEmpty() ? 'slot' : '' }}">
                                        @foreach ($slots as $slot)
                                            <div class="slot">
                                                <div class="teacher-name">{{ $slot->teacher->name }}</div>
                                                <div class="slot-details">
                                                    {{ (int) explode(':', $slot->start_time)[0] }}:00 - {{ (int) explode(':', $slot->end_time)[0] }}:00
                                                </div>
                                                <div class="subject-name">{{ $slot->subject->name }}</div>
                                            </div>
                                        @endforeach
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
