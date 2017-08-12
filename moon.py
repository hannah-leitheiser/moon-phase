# Displays information about the moon phase.
# Assumes the moon's behavior is perfectly periodic.
# moon.py - python3 file
# by Hannah Leitheiser

import time
import math
lunarMonthDays=29.5306 # days
lunarMonth=lunarMonthDays * 86400 # seconds

# ----------- Parse Command Line Arguments ------------

import argparse

parser = argparse.ArgumentParser(description="Display information about the moon's phase.")
parser.add_argument('-s', help='width of moon in characters.',default=40,type=int)
parser.add_argument('-p', help='print current phase only.', action='store_true')
parser.add_argument('-a', help='aspect ratio of moon printout.', default=2, type=int)
parser.add_argument('-f', help='Number of future phases to predict.', default=3, type=int)

args = parser.parse_args()
moonDrawSize=args.s
aspectRatio=args.a
futurePhaseNumber=args.f
printCurrentPhaseOnly=args.p

# ----------------- Preform Calculations -----------------

# compute periods from a known new moon
moonPeriods = -(1503343800 - time.time()) / (29.5306 * 86400)
# computer the phase as a fraction from zero to one.  Zero and one indicating new moon.  0.5 indicating full moon.
moonPhase = moonPeriods - math.floor(moonPeriods)
# Use theta = moonPhase*2pi
# cos(theta) represents one coordinate of a unit circle, or the only relevant coordinate of movement around a circle on edge.  The light at the moon's equator can be imagined as such a circle. 
lightPortion=(1-math.cos(moonPhase*2*math.pi))/2

# ----------------------- Print Results ----------------

# labels for 16ths of the whole lunar cycle.
phaseLabels = ("New Moon",)+("Waxing Crescent",)*3+("First Quarter",)+("Waxing Gibbous",)*3+("Full",)+("Waining Gibbous",)*3+("Third Quarter",)+("Waining Crescent",)*3

# Print current date and time, phase, and illumination in percent.
print("Moon Phase for "+ time.ctime(time.time()) +": " + phaseLabels[ int(moonPhase*16) ])

if(printCurrentPhaseOnly):
    exit()

print("Illumination: {:.2f}%.".format(lightPortion*100))           

# print when the moon will show the next few phases
oldPhase = moonPhase
for x in range(futurePhaseNumber):
    nextPhase = (1+math.floor((oldPhase)*4))/4
    print("Next " + phaseLabels[ int((nextPhase)*16)%16] + ":\n    "+time.ctime (time.time() + (nextPhase-moonPhase)*lunarMonth))
    oldPhase=nextPhase

# ------------------------------------- Draw the Moon -----------------

# Draw the moon, using the moonDrawSize as the width and scaling the height by aspectRatio
for x in range(moonDrawSize//aspectRatio):
    # a represents vertical distance from the center.
    a=(moonDrawSize/2)-(x*aspectRatio)
    # total width on this line
    width=int(math.sqrt((moonDrawSize/2)**2 - a**2)*2)
    light=int(lightPortion*2*math.sqrt((moonDrawSize/2)**2 - a**2))
    dark = width-light
    # whether light or dark is printed first will depend on where the moon is in the cycle.
    if moonPhase < 0.5:
        print((20-width//2)*" "+dark*"-"+loght*"X")
      
    else:
        print((20-width//2)*" "+light*"X"+dark*"-")
