<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Tests\Application\Modules;

use Olifanton\DemoWallet\Application\Modules\DependencyGraph;
use PHPUnit\Framework\TestCase;

class DependencyGraphTest extends TestCase
{
    public function testDependencies(): void
    {
        $graph = new DependencyGraph();
        $graph->requires('A', ['B', 'C']);

        $this->assertEquals(['B', 'C'], $graph->dependencies('A'));
    }

    public function testDependents(): void
    {
        $graph = new DependencyGraph();
        $graph->requires('A', ['B', 'C']);
        $graph->requires('D', ['A', 'C']);

        $this->assertEquals(['A', 'D'], $graph->dependents('C'));
        $this->assertEquals(['D'], $graph->dependents('A'));
        $this->assertEquals(['A'], $graph->dependents('B'));
    }

    public function testGetTopologicalSorted(): void
    {
        $graph = new DependencyGraph();
        $graph->requires('A', ['B', 'C']);
        $graph->requires('D', ['A', 'C']);
        $graph->requires('E', []);

        $this
            ->assertEquals(
                ['B', 'C', 'A', 'D', 'E'],
                $graph->getTopologicalSorted(),
            );
    }

    public function testSelfDependency(): void
    {
        $graph = new DependencyGraph();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Circular dependency found for A and A");

        $graph->requires('A', ['A']);
    }

    public function testCircularDependency(): void
    {
        $graph = new DependencyGraph();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Circular dependency found for A and B");

        $graph->requires('A', ['B']);
        $graph->requires('B', ['A']);
    }
}
