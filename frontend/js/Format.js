export class Format
{
	static Number(number) {
		return new Intl.NumberFormat().format(number)
	}
}
